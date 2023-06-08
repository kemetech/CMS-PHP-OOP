# CMS-PHP

CMS-PHP is a content management system built with PHP. It provides a platform for managing blog posts and user authentication.

## Features

- User authentication: Register, login, and logout functionality for users.
- Role-based access control: Different user roles (admin, author, moderator) with different permissions.
- Blog management: Create, edit, and delete blog posts.
- User management: Create, edit, and delete user accounts.
- Secure sessions: Configurable session settings for enhanced security.
- Image upload: Configurable image upload settings with file size and format restrictions.

## Requirements

- PHP 7.0 or higher
- Apache or compatible web server
- MySQL or compatible database server

## Installation

1. Clone the repository: `git clone https://github.com/moatazelsayed90/CMS-PHP.git`
2. Configure your web server to point to the project's root directory.
3. Import the database: Create a new database and import the SQL dump file provided in the `database.sql` file.
4. Configure the application: Update the database connection settings in the `config.ini` file located in the project's root directory.
5. Customize settings: Modify the `config.ini` file to adjust session and upload configuration settings if needed.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvements, please submit an issue or a pull request.

## License

This project is licensed under the [MIT License](LICENSE).
