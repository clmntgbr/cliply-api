# Cliply API 🎥

A high-performance video clipping API built with **Symfony 8**, **API Platform 4**, and **FrankenPHP**. Cliply allows you to easily extract clips from video files or URLs with a robust, scalable architecture.

## 📋 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Getting Started](#-getting-started)
- [Domain Concepts](#-domain-concepts)
- [API Endpoints](#-api-endpoints)
- [Development](#-development)
- [Quality Assurance](#-quality-assurance)
- [License](#-license)

## 🎯 Overview

Cliply API provides a streamlined interface for video processing, specifically designed for creating short clips from longer video content. It follows **Domain-Driven Design (DDD)** principles to ensure a maintainable and scalable codebase.

## ✨ Key Features

- 🎥 **Video Clipping**: Create clips from hosted URLs or direct file uploads.
- 🚀 **Blazing Fast**: Powered by **FrankenPHP** worker mode for maximum performance.
- 🏗 **Hexagonal Architecture**: Clean separation of concerns (Domain, Application, Infrastructure).
- 🔄 **Real-time Updates**: Built-in **Mercure** support for tracking processing status in real-time.
- 📦 **Cloud Storage**: Integrated with **AWS S3 / MinIO** for video and thumbnail storage.
- 👷 **Async Processing**: **RabbitMQ** & **Symfony Messenger** for reliable background video processing.
- 🔒 **Secure**: JWT authentication via **LexikJWTAuthenticationBundle**.
- 🪝 **Webhooks**: Automatic status notifications to external services.
- 🐘 **PostgreSQL**: Reliable data persistence.

## 🛠 Tech Stack

### Core
- **PHP 8.4**
- **Symfony 8.0**
- **API Platform 4.x**
- **FrankenPHP** (Application Server)

### Infrastructure & Storage
- **PostgreSQL 16** (Database)
- **RabbitMQ 3** (Message Broker)
- **MinIO / AWS S3** (Object Storage)
- **Mercure Hub** (Real-time Messaging)
- **Caddy** (Web Server with automatic HTTPS)

## 🚀 Getting Started

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/) 20.10+
- [Docker Compose](https://docs.docker.com/compose/install/) v2.10+
- [Make](https://www.gnu.org/software/make/)

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd cliply-api
   ```

2. **Setup environment**
   ```bash
   cp .env.dist .env
   # Edit .env with your specific configuration if needed
   ```

3. **Start the application**
   ```bash
   make up
   ```

4. **Initialize the project**
   ```bash
   make jwt    # Generate JWT keys
   make db     # Create database and run migrations
   ```

5. **Access the API Documentation**
   - API Docs (Swagger/Redoc): `http://localhost/api/docs`
   - API Status: `http://localhost/api/status`

## 🧩 Domain Concepts

- **Video**: Represents the source video (file or URL).
- **Clip**: A segment extracted from a Video, with its own lifecycle (Draft -> Processing -> Success/Error).
- **User**: The owner of the videos and clips.

## 🛣 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/clips/url` | Create a clip from a video URL |
| `POST` | `/api/clips/file` | Create a clip from an uploaded file |
| `GET`  | `/api/clips/{id}` | Get clip details and status |
| `GET`  | `/api/clips` | List user clips |

## 💻 Development

### Common Commands

```bash
make up       # Start containers
make down     # Stop containers
make sh       # Access PHP container shell
make logs     # Tail application logs
make consume  # Start the background worker (Messenger)
```

### XDebug
XDebug is pre-configured. Set `XDEBUG_MODE=debug` in your `.env` to enable it.

## 🧪 Quality Assurance

We use a suite of tools to maintain code quality:

```bash
make qa       # Run all QA tools (PHPStan, CS-Fixer, Rector)
make phpstan  # Static analysis
make rector   # Automated refactoring
make php-cs-fixer # Code style enforcement
```

## 📄 License

This project is available under the MIT License. See [LICENSE](LICENSE) for details.

---
Built with ❤️ by the Cliply Team.
