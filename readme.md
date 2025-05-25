# Image to Text Converter with Tesseract OCR

Extract text from images using Tesseract OCR via a simple web interface.

## Prerequisites

- Docker installed on your system
- (Optional) Git for cloning the repository

## Quick Start

### Step 1: Clone the repository
```sh
git clone <-repo-url> # Replace with the actual repo URL
cd <your-project-folder>
```

### Step 2: Build and run
```sh
docker-compose up --build
```

### Step 3: Access the application
Go to: (http://localhost:8080)

---

### About This Project
- Upload images and extract text using Tesseract OCR via a simple PHP web interface.
- No need to install PHP, Composer, or Tesseract on your host—everything runs inside Docker.
- For development with Docker Compose (recommended for persistent uploads/screenshots), use:
  ```sh
  docker-compose up --build
  ```

---

**Happy OCR-ing!**
