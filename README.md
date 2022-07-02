# Symfony 5 Case Study

### İstenenler

- Müşterilerin web servisine kendi kullanıcı adı ve şifreleri ile login olup JWT token alması
- Yeni sipariş oluşturma
- Henüz kargolanmadıysa siparişinde değişiklik yapma
- Sipariş detayını görme
- Tüm siparişleri listeleme

### Altyapı
- Symfony 5, PHP 8, MySQL 5.7, nginx
- JWT için [lexik/jwt-authentication-bundle](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)
- Docker

### Kurulum
- `git clone`
- `docker-compose up -d —build`
- `docker exec -it php bash`
- `composer install`
- `php bin/console make:migration`
- `php bin/console doctrine:fixtures:load`
- `php bin/console lexik:jwt:generate-keypair`
- Postman collection ve env dosyası postman klasörü içindedir.

### API adresleri
- Login: `POST /api/login`
- Sipariş Listesi: `GET /api/order`
- Sipariş Detay: `GET /api/order/{orderNo}`
- Yeni Sipariş: `POST /api/order`
- Sipariş Düzenleme: `PUT /api/order/{orderNo}`

##### Login Parametreleri
| Parametre | Değer                    |
| ------------- | ------------------------------ |
| `username`      | customer1 / customer2 / customer3      |
| `password`   | password    |

##### Sipariş parametreleri POST/PUT
| Parametre | Değer                    |
| ------------- | ------------------------------ |
| `productId`      | integer       |
| `quantity`   | integer     |
| `address`   | string     |

##### Kontroller
- Her müşteri sadece kendisiyle ilişkili siparişlere erişebilir
- Geçerli bir JWT token olmadan login haricindeki sayfalara erişemez.
- Kargoya verilmiş (shippingDate girilmişse) siparişi düzenleyemez.

