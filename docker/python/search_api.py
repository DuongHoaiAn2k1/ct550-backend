from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import mysql.connector
import os
from dotenv import load_dotenv
import pandas as pd

app = FastAPI()

# Cấu hình CORS
origins = [
    "https://client.dacsancamau.com:3001", 
    "http://localhost:3000",  
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"], 
    allow_credentials=True,
    allow_methods=["*"], 
    allow_headers=["*"], 
)

# Load environment variables
load_dotenv()

def get_db_connection():
    """Establish connection to MySQL database."""
    try:
        conn = mysql.connector.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USER"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
            port=os.getenv("DB_PORT")
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

def fetch_products():
    """Fetch products data from MySQL database."""
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT product_id, product_name, product_img, product_des FROM products")
        products = cursor.fetchall()
        cursor.close()
        conn.close()
        return products
    else:
        return []

def preprocess_for_tfidf(products):
    """Preprocess product data by combining name and description, then apply TF-IDF."""
    # Kết hợp tên sản phẩm và mô tả sản phẩm thành một chuỗi
    combined_texts = [f"{product['product_name']} {product['product_des']}" for product in products]
    
    # Chuẩn hóa văn bản (chuyển về chữ thường)
    combined_texts = [text.lower() for text in combined_texts]

    # Khởi tạo TfidfVectorizer từ scikit-learn
    vectorizer = TfidfVectorizer(ngram_range=(1, 2), max_df=0.85)

    # Áp dụng TF-IDF lên các mô tả kết hợp (tên + mô tả sản phẩm)
    tfidf_matrix = vectorizer.fit_transform(combined_texts)

    return tfidf_matrix, vectorizer


class SearchQuery(BaseModel):
    query: str

@app.post("/api/search")
async def search(query: SearchQuery):
    products = fetch_products()
    if not products:
        return {"error": "No products found"}
    
    # Chuẩn hóa truy vấn của người dùng
    query_text = query.query.lower()

    # Áp dụng TF-IDF lên mô tả sản phẩm
    tfidf_matrix, vectorizer = preprocess_for_tfidf(products)

    # Chuyển truy vấn thành ma trận TF-IDF
    query_tfidf = vectorizer.transform([query_text])

    # Tính toán độ tương đồng cosine
    similarities = cosine_similarity(query_tfidf, tfidf_matrix).flatten()

    # Lọc các sản phẩm có độ tương đồng lớn hơn 0.3
    filtered_indices = [i for i, sim in enumerate(similarities) if sim > 0.15]

    # Nếu không có sản phẩm nào đạt yêu cầu
    if not filtered_indices:
        return []

    # Lấy tối đa 5 sản phẩm có độ tương đồng cao nhất
    n = min(5, len(filtered_indices))
    top_indices = sorted(filtered_indices, key=lambda i: similarities[i], reverse=True)[:n]

    # Chuẩn bị kết quả trả về
    results = [{"product_id": products[i]["product_id"], 
                "product_name": products[i]["product_name"], 
                "product_img": products[i]["product_img"], 
                "similarity": similarities[i]} 
               for i in top_indices]

    return results


@app.post("/api/search/all")
async def search_all(query: SearchQuery):
    products = fetch_products()
    if not products:
        return {"error": "No products found"}
    
    # Chuẩn hóa truy vấn của người dùng
    query_text = query.query.lower()

    # Áp dụng TF-IDF lên mô tả sản phẩm
    tfidf_matrix, vectorizer = preprocess_for_tfidf(products)

    # Chuyển truy vấn thành ma trận TF-IDF
    query_tfidf = vectorizer.transform([query_text])

    # Tính toán độ tương đồng cosine
    similarities = cosine_similarity(query_tfidf, tfidf_matrix).flatten()

    # Lọc các sản phẩm có độ tương đồng > 0.3
    filtered_indices = [i for i, similarity in enumerate(similarities) if similarity > 0.15]

    # Sắp xếp theo thứ tự độ tương đồng giảm dần và lấy tối đa 10 sản phẩm
    top_indices = sorted(filtered_indices, key=lambda i: similarities[i], reverse=True)[:10]

    # Chuẩn bị kết quả trả về
    results = [
        {
            "product_id": products[i]["product_id"], 
            "similarity": similarities[i]
        } 
        for i in top_indices
    ]
    
    if not results:
        return {"error": "No products found with similarity above 0.3"}

    return results
