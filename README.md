## Сервіс зі скорочення посилань

### Завдання:
1. Сервіс складається з 2-х сторінок:
   Сторінка з формою для введення посилання + параметри (обмеження
   переходів, час життя)  
   Сторінка 404 (якщо посилання більше не дійсне)
2. У якості дизайну використовується bootstrap.
3. Всі посилання мають наступні параметри:  
   3.1 Обмеження переходів - максимальна кількість переходів за посиланням. 0 = без обмеження  
   3.2 Час життя посилання - задається користувачем, але не більше 24 годин.
4. По закінченню терміну дії посилання або вичерпанню обмеження переходів, при
   переході за коротким посиланням сервіс редирект на сторінку 404.

   Результат:
   Скорочене посилання по переході за посиланням має бути перенаправленням на
   вихідну адресу.
   Токен короткого посилання має бути випадковим, унікальним і складатися з цифр і
   літер (різного регістру) довжиною 8 символів.
   Для реалізації використовувати Laravel.
   Результат повинен бути представлений у вигляді посилання на git репозиторій.
   Буде плюсом використання docker-compose.

### Запуск:
Проект викроистовує Laravel Sail, тому просто "docker-compose up -d" не запрацює. Треба  
1. виконати composer install,  
2. потім скопіювати .env.example в .env та вказати відповідні значеня для паролів  
3. потім vendor/bin/sail up -d  
4. потім vendor/bin/sail shell і в шелі виконати:   
     php artisan key:generate  
     php artisan migrate

Далі вже можна використовувати сервіс.
