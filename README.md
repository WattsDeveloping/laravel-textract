# Payslip Extraction API
@Author Michael Watts

This API allows you to upload a PDF file containing payslip data, extract text from it using AWS Textract, and store the extracted data in a database.

## Requirements

Before you get started, ensure you have the following installed:
- PHP (Laravel framework)
- Composer
- AWS SDK for PHP (already included in this project)
- AWS Access Key ID and Secret Access Key (Contact me if you need access)
- Postman (for testing the API)

## Getting Started

### 1. Install Depencies

First, clone the repository to your local machine:

```bash
cd payslip-extraction-api
composer install
```

### 2. Set Up Environment Variables
```bash
cp .env.example .env

AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret-key
AWS_REGION=us-east-1  # or your preferred AWS region
```

### 3. Migrate the Database
```bash
php artisan migrate
```

### 4. Start laravel Development Server (I use Herd personally)
```bash
php artisan serve
```
## Making API Requests with Postman

### 1. Open Postman
If you don't have Postman installed, you can download it from [here](https://www.postman.com/downloads/).

### 2. Set Up the API Request
In Postman, follow these steps to make a request to the Payslip Extraction API:

- **Method:** `POST`
- **URL:** `http://localhost:8000/api/payslip/upload`
- **Headers:** No specific headers are required, but ensure you set the correct `Content-Type`.
- **Body:** Select `form-data` and add the following field:
    - **Key:** `file`
    - **Value:** Choose the file from your local machine (a PDF file)
    - **Type:** `File`

### 3. Send the Request
Click "Send" in Postman, and you should receive a response with the extracted text from the PDF.

### 4. Sample PaySlip File
You can find a sample payslip PDF file, sample_payslip.pdf, located in the root directory of the project. This file can be used for testing the payslip extraction API.

**Example Response:**

```json
{
  "message": "Text extracted and saved successfully",
  "text": "Extracted text from the payslip PDF..."
}
```

## Set Up Testing Environment
### 1. Copy the .env file to create a .env.testing file:
```bash
cp .env .env.testing

DB_CONNECTION=mysql
DB_DATABASE=laravel_textract_testing
DB_USERNAME=root
DB_PASSWORD=

AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret-key
AWS_REGION=us-east-1
```

### 2. Running Tests
```bash
php artisan test
php artisan test --filter PayslipExtractionTest
```

## Future Improvements

### 1. Store Files on Amazon S3
Currently, I'm storing files locally using Laravel's default storage. However, in a production environment, it's more efficient and scalable to store files on Amazon S3.

### 2. Save Extracted Text as JSON
If the end user knows the specific structure of the PDF (e.g., consistent fields for "Employee Name," "Salary," "Date," etc.), it would be more efficient to parse and save the text as JSON for better querying and manipulation.

### 3. Improved Error Handling
I could improve error handling to catch more specific AWS Textract errors and provide clearer messages to the API consumers. This would involve refining exception handling in the PayslipExtractionService and possibly adding retry
logic for AWS service failures.

### 4. API Rate Limits
AWS Textract may have rate limits, which could affect your requests. I could implement error handling for rate-limiting scenarios, especially when processing multiple files in a short time frame.

### 5. Further tests
If I had more time I could have added tests to mock AWS analyzeDocument
