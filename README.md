# Authentication API

## Overview
This API provides authentication-related endpoints for user login, verification, password reset, and logout.

## Base URL
```
{your-api-domain}/auth
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
  "email": "user@example.com",
  "password": "yourpassword"
}
```

**Response:**
```json
{
  "token": "your-jwt-token",
  "user": { ...user data }
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
  "email": "user@example.com",
  "code": "123456"
}
```

**Response:**
```json
{
  "message": "Code verified successfully"
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
  "message": "Password reset link sent successfully"
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
  "email": "user@example.com",
  "token": "reset-token",
  "new_password": "newpassword"
}
```

**Response:**
```json
{
  "message": "Password reset successfully"
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
  "Authorization": "Bearer your-jwt-token"
}
```

**Response:**
```json
{
  "message": "User logged out successfully"
}
```

---

## Authentication
- All routes (except login, verification, and password reset) require a valid JWT token.
- Include the token in the `Authorization` header as `Bearer {token}` for protected routes.

## Notes
- Ensure you replace `{your-api-domain}` with the actual API domain.
- Tokens expire after a certain period; users must re-authenticate when needed.

