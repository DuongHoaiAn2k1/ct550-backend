from typing import Any, Text, Dict, List
import requests
import asyncio
from rasa_sdk import Action, Tracker
from rasa_sdk.executor import CollectingDispatcher
from rasa_sdk.events import SlotSet
import mysql.connector
import os
from dotenv import load_dotenv

# Load biến môi trường từ .env
load_dotenv()

def get_db_connection():
    """Kết nối MySQL"""
    try:
        conn = mysql.connector.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USER"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
            port=os.getenv("DB_PORT"),
        )
        return conn
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

def fetch_categories():
    """Truy vấn danh mục sản phẩm từ MySQL"""
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT category_name FROM categories")
        categories = [row["category_name"] for row in cursor.fetchall()]
        cursor.close()
        conn.close()
        return categories
    else:
        return []
    
def fetch_promotions() -> List[str]:
    """Lấy danh sách các chương trình khuyến mãi từ cơ sở dữ liệu"""
    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT promotion_name FROM promotions WHERE status = 'active'")
        promotions = cursor.fetchall()  # Fetch all results
        cursor.close()
        conn.close()

        # Nếu có kết quả, trả về danh sách tên các chương trình khuyến mãi
        return [promo[0] for promo in promotions]

    except Exception as e:
        print(f"Lỗi khi truy vấn cơ sở dữ liệu: {e}")
        return []

class ActionFetchCategories(Action):
    def name(self) -> Text:
        return "action_fetch_categories"

    async def run(
        self,
        dispatcher: CollectingDispatcher,
        tracker: Tracker,
        domain: Dict[Text, Any],
    ) -> List[Dict[Text, Any]]:
        """Hành động lấy danh mục sản phẩm"""
        categories = fetch_categories()

        if categories:
            category_list = ", ".join(categories)
            message = f"Chúng tôi hiện có các danh mục sản phẩm sau: {category_list}."
        else:
            message = "Hiện tại không có danh mục sản phẩm nào được tìm thấy."

        dispatcher.utter_message(text=message)
        return []

class MockDispatcher:
    def utter_message(self, text=None, json_message=None):
        """Dispatcher mô phỏng để kiểm tra hành động"""
        if json_message is not None:
            print(f"DISPATCHER JSON MESSAGE: {json_message}")
        elif text is not None:
            print(f"DISPATCHER TEXT MESSAGE: {text}")

class ActionSearchProducts(Action):
    def name(self) -> str:
        return "action_search_products"

    async def run(self, dispatcher=None, tracker=None, domain: Dict[str, Any] = None):
        # Lấy entity product từ tracker
        entities = tracker.latest_message.get("entities", [])
        product_query = None

        for entity in entities:
            if entity.get("entity") == "product":
                product_query = entity.get("value")
                break

        # Nếu không có entity, sử dụng toàn bộ câu hỏi
        if not product_query:
            product_query = tracker.latest_message.get("text")

        # API URL
        api_url = "http://python-search:8000/api/search/all"
        payload = {"query": product_query}  # Sử dụng truy vấn từ entity hoặc câu hỏi

        try:
            # Gửi yêu cầu đến API
            response = requests.post(api_url, json=payload)

            if response.status_code == 200:
                results = response.json()
                if "error" in results:
                    dispatcher.utter_message(json_message={"results": []})
                else:
                    dispatcher.utter_message(json_message={"results": results})
            else:
                dispatcher.utter_message(json_message={"results": []})
        except Exception as e:
            dispatcher.utter_message(json_message={"error": str(e)})

        return []

class MockTracker(Tracker):
    """Giả lập tracker để mô phỏng thông điệp từ người dùng"""
    def __init__(self):
        self.latest_message = {"text": "Hiện tại có chương trình khuyến mãi nào?"}
        self.slots = {}

    def get_slot(self, key):
        return self.slots.get(key)

    def latest_message(self):
        return self.latest_message


class ActionShowActivePromotions(Action):
    def name(self) -> Text:
        return "action_show_active_promotions"

    async def run(
        self,
        dispatcher: CollectingDispatcher,
        tracker: Tracker,
        domain: Dict[Text, Any],
    ) -> List[Dict[Text, Any]]:
        """Hành động lấy danh sách các chương trình khuyến mãi"""

        try:
            # Kết nối cơ sở dữ liệu và truy vấn thông tin chương trình khuyến mãi
            promotions = fetch_promotions()  # Giả sử đây là hàm lấy dữ liệu khuyến mãi từ cơ sở dữ liệu

            if promotions:
                # Chuyển danh sách chương trình khuyến mãi thành một chuỗi
                promotion_list = ", ".join(promotions)
                message = f"Các chương trình khuyến mãi hiện tại là: {promotion_list}."
            else:
                message = "Hiện tại không có chương trình khuyến mãi nào được tìm thấy."

        except Exception as e:
            message = f"Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu: {str(e)}"

        # Gửi thông điệp cho người dùng
        dispatcher.utter_message(text=message)

        return []

class ActionGetActivePromotions(Action):
    def name(self) -> str:
        return "action_get_active_offers"

    async def run(self, dispatcher=None, tracker=None, domain: Dict[str, Any] = None):
        # Kết nối cơ sở dữ liệu MySQL
        conn = mysql.connector.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USER"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
            port=os.getenv("DB_PORT"),
        )

        cursor = conn.cursor(dictionary=True)

        # Truy vấn để lấy các sản phẩm khuyến mãi có trạng thái 'active'
        query = """
            SELECT p.product_id
            FROM promotions pr
            JOIN product_promotions pp ON pr.promotion_id = pp.promotion_id
            JOIN products p ON pp.product_id = p.product_id
            WHERE pr.status = 'active'
            LIMIT 4;
        """

        cursor.execute(query)
        promotions = cursor.fetchall()

        # Tạo dữ liệu JSON trả về
        response_data = {"results": []}

        if promotions:
            for promo in promotions:
                # Thêm trường similarity = 1 cho mỗi sản phẩm
                product_info = {
                    "product_id": promo["product_id"],
                    "similarity": 1  # Thêm giá trị similarity mặc định là 1
                }
                response_data["results"].append(product_info)
        else:
            response_data["results"] = []

        # Gửi phản hồi dưới dạng JSON
        dispatcher.utter_message(json_message=response_data)

        cursor.close()
        conn.close()

        return []
    
class ActionGetHighestPricedProduct(Action):
    def name(self) -> str:
        return "action_get_highest_priced_product"

    async def run(self, dispatcher: CollectingDispatcher, tracker: Tracker, domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        """Truy vấn sản phẩm có giá cao nhất và trả về thông tin theo định dạng giống ActionGetActivePromotions"""
        try:
            # Kết nối cơ sở dữ liệu MySQL
            conn = mysql.connector.connect(
                host=os.getenv("DB_HOST"),
                user=os.getenv("DB_USER"),
                password=os.getenv("DB_PASSWORD"),
                database=os.getenv("DB_NAME"),
                port=os.getenv("DB_PORT"),
            )
            cursor = conn.cursor(dictionary=True)

            # Truy vấn để lấy sản phẩm có giá cao nhất
            query = """
                SELECT product_id, product_name, product_price
                FROM products
                ORDER BY product_price DESC
                LIMIT 1;
            """
            cursor.execute(query)
            highest_priced_product = cursor.fetchone()

            # Khởi tạo phản hồi JSON
            response_data = {"results": []}

            if highest_priced_product:
                # Lấy thông tin sản phẩm
                product_info = {
                    "product_id": highest_priced_product["product_id"],
                    "similarity": 3  # Thêm giá trị similarity mặc định là 1
                }
                response_data["results"].append(product_info)
            else:
                response_data["results"] = []

            # Gửi thông điệp JSON về cho người dùng
            dispatcher.utter_message(json_message=response_data)

            # Đóng kết nối cơ sở dữ liệu
            cursor.close()
            conn.close()

        except Exception as e:
            dispatcher.utter_message(text=f"Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu: {str(e)}")

        return []
    
class ActionGetLowestPricedProduct(Action):
    def name(self) -> str:
        return "action_get_lowest_priced_product"

    async def run(self, dispatcher: CollectingDispatcher, tracker: Tracker, domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        """Truy vấn sản phẩm có giá thấp nhất và trả về thông tin theo định dạng giống ActionGetActivePromotions"""
        try:
            # Kết nối cơ sở dữ liệu MySQL
            conn = mysql.connector.connect(
                host=os.getenv("DB_HOST"),
                user=os.getenv("DB_USER"),
                password=os.getenv("DB_PASSWORD"),
                database=os.getenv("DB_NAME"),
                port=os.getenv("DB_PORT"),
            )
            cursor = conn.cursor(dictionary=True)

            # Truy vấn để lấy sản phẩm có giá thấp nhất
            query = """
                SELECT product_id, product_name, product_price
                FROM products
                ORDER BY product_price ASC
                LIMIT 1;
            """
            cursor.execute(query)
            lowest_priced_product = cursor.fetchone()

            # Khởi tạo phản hồi JSON
            response_data = {"results": []}

            if lowest_priced_product:
                # Lấy thông tin sản phẩm
                product_info = {
                    "product_id": lowest_priced_product["product_id"],
                    "similarity": 2  # Thêm giá trị similarity mặc định là 1
                }
                response_data["results"].append(product_info)
            else:
                response_data["results"] = []

            # Gửi thông điệp JSON về cho người dùng
            dispatcher.utter_message(json_message=response_data)

            # Đóng kết nối cơ sở dữ liệu
            cursor.close()
            conn.close()

        except Exception as e:
            dispatcher.utter_message(text=f"Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu: {str(e)}")

        return []
    
class ActionFetchMostBoughtProduct(Action):
    def name(self) -> str:
        return "action_fetch_most_bought_product"

    async def run(self, dispatcher: CollectingDispatcher, tracker: Tracker, domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        """Truy vấn sản phẩm được mua nhiều nhất và trả về thông tin theo định dạng giống ActionGetLowestPricedProduct"""
        try:
            # Kết nối cơ sở dữ liệu MySQL
            conn = mysql.connector.connect(
                host=os.getenv("DB_HOST"),
                user=os.getenv("DB_USER"),
                password=os.getenv("DB_PASSWORD"),
                database=os.getenv("DB_NAME"),
                port=os.getenv("DB_PORT"),
            )
            cursor = conn.cursor(dictionary=True)

            # Truy vấn để lấy sản phẩm được mua nhiều nhất (sử dụng JOIN để kết hợp bảng order_detail và products)
            query = """
                SELECT p.product_id, p.product_name, SUM(od.quantity) AS total_quantity
                FROM order_detail od
                JOIN products p ON od.product_id = p.product_id
                GROUP BY p.product_id
                ORDER BY total_quantity DESC
                LIMIT 1;
            """
            cursor.execute(query)
            most_bought_product = cursor.fetchone()

            # Khởi tạo phản hồi JSON
            response_data = {"results": []}

            if most_bought_product:
                # Lấy thông tin sản phẩm
                product_info = {
                    "product_id": most_bought_product["product_id"],
                    "product_name": most_bought_product["product_name"],
                    "similarity": 4  # Similarity là 4 như yêu cầu
                }
                response_data["results"].append(product_info)
            else:
                response_data["results"] = []

            # Gửi thông điệp JSON về cho người dùng
            dispatcher.utter_message(json_message=response_data)

            # Đóng kết nối cơ sở dữ liệu
            cursor.close()
            conn.close()

        except Exception as e:
            dispatcher.utter_message(text=f"Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu: {str(e)}")

        return []

class ActionFetchMostLovedProduct(Action):
    def name(self) -> str:
        return "action_fetch_most_loved_product"

    async def run(self, dispatcher: CollectingDispatcher, tracker: Tracker, domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
        """Truy vấn sản phẩm có điểm đánh giá trung bình cao nhất và trả về thông tin theo định dạng giống ActionGetLowestPricedProduct"""
        try:
            # Kết nối cơ sở dữ liệu MySQL
            conn = mysql.connector.connect(
                host=os.getenv("DB_HOST"),
                user=os.getenv("DB_USER"),
                password=os.getenv("DB_PASSWORD"),
                database=os.getenv("DB_NAME"),
                port=os.getenv("DB_PORT"),
            )
            cursor = conn.cursor(dictionary=True)

            # Truy vấn để lấy sản phẩm có điểm đánh giá trung bình cao nhất
            query = """
                SELECT p.product_id, p.product_name, AVG(r.rating) AS average_rating
                FROM reviews r
                JOIN products p ON r.product_id = p.product_id
                GROUP BY p.product_id
                ORDER BY average_rating DESC
                LIMIT 1;
            """
            cursor.execute(query)
            most_loved_product = cursor.fetchone()

            # Khởi tạo phản hồi JSON
            response_data = {"results": []}

            if most_loved_product:
                # Lấy thông tin sản phẩm yêu thích nhất
                product_info = {
                    "product_id": most_loved_product["product_id"],
                    "product_name": most_loved_product["product_name"],
                    # "average_rating": most_loved_product["average_rating"],
                    "similarity": 5 
                }
                response_data["results"].append(product_info)
            else:
                response_data["results"] = []

            # Gửi thông điệp JSON về cho người dùng
            dispatcher.utter_message(json_message=response_data)

            # Đóng kết nối cơ sở dữ liệu
            cursor.close()
            conn.close()

        except Exception as e:
            dispatcher.utter_message(text=f"Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu: {str(e)}")

        return []


if __name__ == "__main__":
    print("Đang kiểm tra ActionShowActivePromotions...")
    
    # Khởi tạo đối tượng dispatcher giả lập
    dispatcher = MockDispatcher()
    
    # Khởi tạo tracker giả lập
    tracker = MockTracker()

    # Khởi tạo action
    # action = ActionShowActivePromotions()

    action2 = ActionFetchMostLovedProduct()

    # Chạy thử action
    import asyncio
    # asyncio.run(action.run(dispatcher=dispatcher, tracker=tracker, domain={}))

    asyncio.run(action2.run(dispatcher=dispatcher, tracker=tracker, domain={}))
