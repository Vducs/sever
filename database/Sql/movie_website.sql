-- Tạo cơ sở dữ liệu
CREATE DATABASE IF NOT EXISTS movie_website;
USE movie_website;

-- Bảng users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'user',
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng categories
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng genres
CREATE TABLE genres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng movies
CREATE TABLE movies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    image VARCHAR(255) NULL,
    views BIGINT UNSIGNED NOT NULL DEFAULT 0,
    rating FLOAT NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng episodes
CREATE TABLE episodes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    movie_id BIGINT UNSIGNED NOT NULL,
    episode_number INT UNSIGNED NOT NULL,
    url VARCHAR(255) NULL,
    duration INT UNSIGNED NULL,
    published_at TIMESTAMP NULL DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng genre_movie
CREATE TABLE genre_movie (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    genre_id BIGINT UNSIGNED NOT NULL,
    movie_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn dữ liệu mẫu vào users
INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00');

-- Chèn dữ liệu mẫu vào categories
INSERT INTO categories (name, description, image, published_at, status, created_at, updated_at) VALUES
('Hành động', 'Phim hành động kịch tính', 'action_poster.jpg', '2025-05-01 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Hài hước', 'Phim hài giải trí', 'comedy_poster.jpg', '2025-05-02 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Kinh dị', 'Phim kinh dị rùng rợn', 'horror_poster.jpg', '2025-05-03 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00');

-- Chèn dữ liệu mẫu vào genres
INSERT INTO genres (name, description, image, published_at, status, created_at, updated_at) VALUES
('Khoa học viễn tưởng', 'Phim về khoa học và tương lai', 'sci-fi_poster.jpg', '2025-05-01 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Phiêu lưu', 'Phim về hành trình khám phá', 'adventure_poster.jpg', '2025-05-02 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Tình cảm', 'Phim lãng mạn', 'romance_poster.jpg', '2025-05-03 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Hành động', 'Phim hành động gay cấn', 'action_genre_poster.jpg', '2025-05-04 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00');

-- Chèn dữ liệu mẫu vào movies
INSERT INTO movies (title, description, category_id, image, views, rating, published_at, created_by, status, created_at, updated_at) VALUES
('Chiến Binh Vũ Trụ', 'Một chiến binh từ tương lai', 1, 'movie1_poster.jpg', 1000, 4.5, '2025-05-10 00:00:00', 1, 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Cuộc Phiêu Lưu Hài Hước', 'Hành trình hài hước của một nhóm bạn', 2, 'movie2_poster.jpg', 500, 4.0, '2025-05-15 00:00:00', 2, 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Bóng Tối Kinh Hoàng', 'Một ngôi nhà ma ám', 3, 'movie3_poster.jpg', 800, 4.2, '2025-05-20 00:00:00', 3, 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00');

-- Chèn dữ liệu mẫu vào episodes
INSERT INTO episodes (title, movie_id, episode_number, url, duration, published_at, status, created_at, updated_at) VALUES
('Tập 1: Khởi đầu', 1, 1, 'https://example.com/stream/ep1', 45, '2025-05-10 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Tập 2: Trận chiến', 1, 2, 'https://example.com/stream/ep2', 50, '2025-05-11 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Tập 1: Hành trình bắt đầu', 2, 1, 'https://example.com/stream/ep3', 40, '2025-05-15 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'),
('Tập 1: Gặp ma', 3, 1, 'https://example.com/stream/ep4', 55, '2025-05-20 00:00:00', 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00');

-- Chèn dữ liệu mẫu vào genre_movie
INSERT INTO genre_movie (genre_id, movie_id, created_at, updated_at) VALUES
(1, 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'), -- Chiến Binh Vũ Trụ - Khoa học viễn tưởng
(4, 1, '2025-05-28 00:14:00', '2025-05-28 00:14:00'), -- Chiến Binh Vũ Trụ - Hành động
(2, 2, '2025-05-28 00:14:00', '2025-05-28 00:14:00'), -- Cuộc Phiêu Lưu Hài Hước - Phiêu lưu
(2, 3, '2025-05-28 00:14:00', '2025-05-28 00:14:00'), -- Bóng Tối Kinh Hoàng - Phiêu lưu
(3, 3, '2025-05-28 00:14:00', '2025-05-28 00:14:00'); -- Bóng Tối Kinh Hoàng - Tình cảm