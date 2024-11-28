FROM rasa/rasa-sdk:latest

# Switch to root user
USER root

# Copy source code and requirements
COPY . /app

# Install dependencies
RUN pip install --no-cache-dir -r /app/requirements-actions.txt

# Set working directory
WORKDIR /app

# Expose port for the action server
EXPOSE 5055

# Command to run the action server
CMD ["python", "-m", "rasa_sdk", "--actions", "actions"]
