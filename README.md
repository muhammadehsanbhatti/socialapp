

# JUGU APP

It is jugu app.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Controllers](#controllers)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)

## Project Overview

This project serves as a communication platform with the following key features and functionalities:

- **Connection:** Users can establish connections with other users, enabling one-on-one communication.
Single Messages: Users can send individual messages to other users in their network. These messages are private and only visible to the sender and receiver.

- **Group Messages:** Users can create and participate in group conversations. Group messages facilitate communication among multiple users within a specific topic or context.

- **Broadcast Messages:** Administrators or authorized users can broadcast messages to a large audience. This feature is useful for announcements, updates, or important notifications that need to reach all users.

- **Pitch Modules**: The project includes modules for creating, managing, and delivering pitch presentations. Users can create dynamic pitch decks, add multimedia content, and share them with specific individuals or groups.

## Features

List the key features and functionalities of the project. You can include bullet points or a brief description for each feature.

- Connection
- Single messages
- Group messages
- Broadcast messages
- Pitch modules

## Controllers

- **BaseController:** Manages common functionalities shared across multiple controllers.

- **ConnectionBookMarkController:** Handles bookmarking of connections between users.

- **FCM_TokenController:** Controls the management of Firebase Cloud Messaging tokens for push notifications.

- **GroupController:** Manages operations related to user groups or group messaging.

- **MessageController:** Handles sending, receiving, and managing messages between users.

- **PitchBookMarkController:** Manages bookmarking of pitches or project ideas.

- **PitchController:** Controls operations related to pitches or project proposals.

- **RegisterController:** Handles user registration and authentication processes.

- **SettingController:** Manages user settings and preferences.

- **UserController:** Controls user-related operations such as profile management and user search.

## Installation

1. Clone the repository:

    ```
    git clone git@github.com:jugudev/jugu.git
    ```
2. Install dependencies.

    ```
    composer install
    ```
3. Set up environment variables.

    ```
    cp .env.example .env
    php artisan key:generate
    ```
4. Run migrations.

    ```
    php artisan migrate
    ```

## Usage

It is a social app

## contributing

Contributions are welcome! Please follow the guidelines outlined in README.md 


# Visit in your browser

https://app.jugu.io
