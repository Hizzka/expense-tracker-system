# Security Policy

## Reporting a Vulnerability

If you discover a security vulnerability in the Expense Tracker System, please report it by emailing: your.email@example.com

Please include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

## Security Best Practices

### For Developers
- Always use prepared statements for database queries
- Sanitize all user inputs
- Use password_hash() for passwords
- Keep PHP and MySQL updated
- Validate and sanitize file uploads
- Use HTTPS in production

### For Users
- Use strong passwords (min 12 characters)
- Never share your credentials
- Log out when finished
- Keep your browser updated
- Be cautious of phishing attempts

## Known Security Considerations

### Current Implementation
- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention via prepared statements
- ✅ XSS protection via htmlspecialchars()
- ✅ Session-based authentication
- ⚠️ CSRF tokens not fully implemented
- ⚠️ No rate limiting on login attempts
- ⚠️ No email verification

### Recommended for Production
1. Implement CSRF tokens for all forms
2. Add rate limiting for login attempts
3. Enable HTTPS/SSL
4. Implement 2FA (Two-Factor Authentication)
5. Add email verification
6. Set up regular backups
7. Use environment variables for sensitive config
8. Implement proper error logging
9. Add IP-based access restrictions
10. Regular security audits

## Updates

This security policy was last updated on: November 23, 2025
