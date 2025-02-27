# Guitar Chord Dictionary
   
A web application for exploring guitar chords, creating chord progressions, and saving your favorite sequences.

## Features

- Search and visualize guitar chords with accurate fingering diagrams
- Create and save custom chord progressions
- User authentication system
- Development mode for testing without a backend

## Setup

### Prerequisites

- Node.js (v14 or higher)
- PHP 7.4+ (for backend)
- MySQL (for database)

### Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/chord-dictionary.git
   cd chord-dictionary
   ```

2. Install frontend dependencies:
   ```
   npm install
   ```

3. Set up environment variables:
   - Copy `.env.example` to `.env`
   - Update the values in `.env` to match your environment

4. Set up the database:
   - Create a MySQL database
   - Import the schema from `backend/config/schema.sql`
   - Update database credentials in `.env`

5. Start the PHP development server for the backend:
   ```
   php -S localhost:8000 -t backend
   ```

6. Start the frontend development server:
   ```
   npm run dev
   ```

7. Open your browser and navigate to `http://localhost:5173`

## Environment Variables

The application uses environment variables for configuration. Create a `.env` file in the root directory with the following variables:

```
# App Environment
VITE_APP_ENV=development

# API Configuration
VITE_API_URL=/backend

# Database Configuration (for backend)
VITE_DB_HOST=localhost
VITE_DB_NAME=chord_dictionary
VITE_DB_USER=your_db_username
VITE_DB_PASS=your_db_password
VITE_DB_SOCKET=/path/to/mysql/socket  # Optional, for socket connections

# Authentication
VITE_AUTH_SECRET=your_secret_key_here
VITE_AUTH_EXPIRY=86400

# Development Mode
VITE_DEV_MODE=true
```

### Environment Variables Explanation

- `VITE_APP_ENV`: The application environment (development, production)
- `VITE_API_URL`: The base URL for API requests
- `VITE_DB_HOST`: Database host (can include port, e.g., localhost:8889 for MAMP)
- `VITE_DB_NAME`: Database name
- `VITE_DB_USER`: Database username
- `VITE_DB_PASS`: Database password
- `VITE_DB_SOCKET`: Path to MySQL socket file (optional, for socket connections)
- `VITE_AUTH_SECRET`: Secret key for authentication tokens
- `VITE_AUTH_EXPIRY`: Token expiry time in seconds
- `VITE_DEV_MODE`: Enable development mode features

### Database Connection

The application supports both TCP/IP and socket connections to MySQL:

- **TCP/IP Connection**: Set `VITE_DB_HOST` to your database host, optionally with a port (e.g., `localhost:8889` for MAMP)
- **Socket Connection**: Set `VITE_DB_SOCKET` to the path of your MySQL socket file

#### Common Socket Paths

- MAMP: `/Applications/MAMP/tmp/mysql/mysql.sock`
- Default MySQL: `/tmp/mysql.sock` or `/var/run/mysqld/mysqld.sock`

If you're using MAMP with the default configuration, use:

```
VITE_DB_HOST=localhost:8889
VITE_DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```

## Development Mode

The application includes a development mode that allows you to test features without a backend. To enable development mode:

1. Set `VITE_DEV_MODE=true` in your `.env` file
2. Start the application with `npm run dev`
3. Use the development mode toggle in the bottom left corner of the application

In development mode, the application will:
- Simulate API responses
- Allow you to "log in" with any username/password
- Save data to localStorage instead of a database

## License

MIT