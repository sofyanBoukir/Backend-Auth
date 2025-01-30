# Authentication API

## Overview
This API provides authentication-related endpoints for user login, verification, password reset, and logout.

## Installation

### Prerequisites
- PHP 8+
- Composer
- Laravel Framework Installed
- MySQL database

### Setup
1. Clone the repository:
   ```sh
   git clone https://github.com/sofyanBoukir/Backend-Auth.git
   cd Backend-Auth
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Copy the environment file and configure the database:
   ```sh
   cp .env.example .env
   ```
   Update `.env` with your database credentials IMPORTANT
   
5. Setup your smtp data IMPORTANT
   ```sh
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=465 
    MAIL_USERNAME=soufianeexample01@example.com
    MAIL_PASSWORD=************
    MAIL_FROM_ADDRESS="soufianeexample01@example.com"
    MAIL_FROM_NAME="SOFYAN"
   ```

6. Setup your frontend (Example) on `.env`
    ```
    FRONTEND_URL=http://localhost:5173
    ```

7. Generate application key:
   ```sh
   php artisan key:generate
   ```
8. Run migrations:
   ```sh
   php artisan migrate
   ```
9. Install and configure JWT authentication:
   ```sh
   php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
   ```
   Generate JWT secret key:
   ```sh
   php artisan jwt:secret
   ```
10. Start the development server:
   ```sh
   php artisan serve
   ```

## Base URL
```
http://localhost:8000/api/auth
```

## Endpoints

### 1. User Login
**Endpoint:**
```
POST /auth/login
```
**Description:**
Authenticate a user using email and password.

**Request Body:**
```json
{
  "email": "test@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "token" : "token gived from server",
  "userDara": {
            "id" : 1,
            "name" : "Test User",
            "email" : "test@example.com",
            "email_verified_at" : "2025-01-30T12:02:08.000000Z",
            "created_at" : "2025-01-30T12:02:09.000000Z",
            "updated_at" : "2025-01-30T12:02:09.000000Z"
        }
}
```
Or:
```json
{
    "message" : "Email or password incorrect"
}
```

---

### 2. Send Verification Code
**Endpoint:**
```
POST /auth/sendVerificationCode
```
**Description:**
Sends a verification code to the registered email.
**Note!:**
Verification code expires in 2 minutes, you can modify it on line 64 `now()->addMinutes(2)`

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

**Response:**
```json
{
  "message": "Verification code sent successfully"
}
```
OR:
```json
{
  "message": "User with this email already exists"
}
```

---

### 3. Verify Code
**Endpoint:**
```
POST /auth/verifyCode
```
**Description:**
Verifies the code sent to the user's email.

**Request Body:**
```json
{
  "email" : "user@example.com",
  "code" : "123456",
  "fullName" : "Sofyan bou",
  "password" : "1234"
}
```

**Response:**

```json
{
  "message": "Successfully registred"
}
```
Or:
```json
{
  "message": "Verification code expired or incorrect!"
}
```

---

### 4. Send Password Reset Link
**Endpoint:**
```
POST /auth/sendResetLink
```
**Description:**
Sends a password reset link to the provided email.

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

**Response:**
```json
{
  "message": "Your reset link has been sent to your email"
}
```
Or:
```json
{
  "message": "User with this email does not exist"
}
```

---

### 5. Reset Password
**Endpoint:**
```
POST /auth/resetPassword
```
**Description:**
Resets the user's password using the provided token.

**Request Body:**
```json
{
  "email" : "user@example.com from the URL",
  "token" : "reset-token from the URL",
  "password" : "12345",
  "password_confirmation" : "12345"
}
```

**Response:**
```json
{
  "message" : "Password reseted successfully!"
}
```
Or:
```json
{
  "message" : "This password reset token is invalid."
}
```

---

### 6. Logout
**Endpoint:**
```
POST /auth/logout
```
**Description:**
Logs out the authenticated user.

**Request Headers:**
```json
{
  "Authorization": "Bearer {token}"
}
```

**Response:**
```json
{
  "message": "User logged out successfully"
}
```
Or:
```json
{
  "message": "Token Signature could not be verified."
}
```

---

## Authentication
- Only logout route requires a valid JWT token.
- Include the token in the `Authorization` header as `Bearer {token}`.

## Notes
- Configure your database credentianls on `.env` file.
- Configure your smtp credentials on `.env` file.
- Configure your frontend base-url on `.env` file.
- Tokens expire after 60 mins a certain period; users must re-authenticate when needed.
- Configure the token time to live in `config/jwt.php` on `104` line `minutes` if you want.

