# Contributing to Expense Tracker System

Thank you for considering contributing to the Expense Tracker System! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- Clear title and description
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)
- Your environment (PHP version, browser, OS)

### Suggesting Features

Feature requests are welcome! Please:
- Check if the feature has already been requested
- Provide a clear use case
- Explain how it benefits users
- Include mockups/examples if possible

### Code Contributions

1. **Fork the repository**
   ```bash
   git clone https://github.com/yourusername/expense-tracker-system.git
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Follow the existing code style
   - Comment your code where necessary
   - Test thoroughly

4. **Commit your changes**
   ```bash
   git commit -m "Add: Brief description of changes"
   ```

5. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Create a Pull Request**
   - Provide a clear description of changes
   - Reference any related issues
   - Include screenshots for UI changes

## Code Standards

### PHP
- Use PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Use prepared statements for all queries
- Sanitize all user inputs

### HTML/CSS
- Use semantic HTML5 elements
- Follow Bootstrap conventions
- Keep CSS organized and commented
- Ensure responsive design

### JavaScript
- Use ES6+ features
- Keep functions small and focused
- Add comments for complex operations
- Test across different browsers

### Database
- Use descriptive table and column names
- Add appropriate indexes
- Document schema changes
- Include migration scripts

## Testing

Before submitting:
- Test all functionality
- Verify on different browsers
- Check mobile responsiveness
- Ensure no console errors
- Test with different data scenarios

## Pull Request Process

1. Update README.md if needed
2. Update documentation for new features
3. Ensure all tests pass
4. Get approval from maintainer
5. Squash commits if requested

## Code Review

All submissions require review. We'll look for:
- Code quality and standards
- Security considerations
- Performance impact
- Documentation completeness
- Test coverage

## Development Setup

```bash
# Clone repository
git clone https://github.com/yourusername/expense-tracker-system.git

# Set up database
mysql -u root -p < database.sql

# Configure settings
cp config.php.example config.php
# Edit config.php with your settings

# Start development server
php -S localhost:8000
```

## Questions?

Feel free to open an issue for any questions about contributing.

Thank you for making Expense Tracker better! 🚀
