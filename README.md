#  Laravel API Project

Микросервисная RESTFUL API для управления пользователями, компаниями и отзывами.  
Проект реализован на Laravel с использованием Docker-окружения и PostgreSQL.

##  Описание

REST API с возможностью:
- Управления пользователями и компаниями
- Оставления комментариев с рейтингом
- Загрузки файлов
- Получения аналитики по компаниям
- Логирования операций

##  Технологии и приложения

- **Laravel 11** 
- **PostgreSQL** 
- **Docker** 
- **Nginx** 
- **PHP**
- **PhpStorm**
- **Postman**

## Старт

### Предварительные требования
- Docker
- Docker Compose

```bash
# 1. Клонировать репозиторий
git clone <URL-репозитория>
cd <имя-проекта>

2. Настроить окружение
.env
# Окружение в .env настроено

3. Запустить контейнеры
docker compose up -d

4. Установить зависимости
docker compose exec php-fpm composer install

5. Создать симлинк для файлов
php artisan storage:link

6.Запустить миграций
php artisan migrate

Приложение доступно по адресу:
http://localhost:84

API Документация
 
Изображения (Files)

Метод   Путь   Описание
POST api/v1/files/upload Загрузить изображение
 
Пользователи (Users)
Метод	Путь	Описание
GET	/api/v1/users	Список пользователей
POST	/api/v1/users	Создать пользователя
GET	/api/v1/users/{id}	Получить пользователя
PUT	/api/v1/users/{id}	Обновить пользователя
DELETE	/api/v1/users/{id}	Удалить пользователя

Компании (Companies)
Метод	Путь	Описание
GET	/api/v1/companies	Список компаний
POST	/api/v1/companies	Создать компанию
GET	/api/v1/companies/{id}	Получить компанию
PUT	/api/v1/companies/{id}	Обновить компанию
DELETE	/api/v1/companies/{id}	Удалить компанию
GET	/api/v1/companies/{id}/comments	Комментарии компании
GET	/api/v1/companies/{id}/rating	Средний рейтинг компании
GET	/api/v1/companies/top/rated	Топ компаний по рейтингу
Файлы (Files)

Комментарии (Comments)
 Метод   Путь                      Описание                          
 GET     /api/v1/comments         Список всех комментариев         
 POST   /api/v1/comments         Создать новый комментарий         
 GET     /api/v1/comments/{id}    Получить комментарий по ID        
 PUT     /api/v1/comments/{id}    Обновить комментарий              
 DELETE  /api/v1/comments/{id}    Удалить комментарий               

 🧪 Тестирование API

⚠️ **Важно!** Для полноценного тестирования системы рекомендуется использовать Postman для тестирования API, чтобы легко создавать запросы, отправлять данные и получать ответы от сервера:
1. Создавать пользователей через `/api/v1/users` 
2. Создавать компании через `/api/v1/companies`
3. Создавать комментарий через `/api/v1/comments
4. Использовать реальные изображения для моделирования рабочих сценариев

### Пример cценария

Создание пользователя:
POST http://localhost:84/api/v1/users
Content-Type: application/json


Завершение работы

Для остановки всех контейнеров используйте:
docker compose down
