Projeto de e-commerce da Foccus
ter o Xamp: 8.2.12 instalado
como instalar localmente:
usando git bash
git clone https://github.com/DevsFatecanos/PI-3-Semestre
e configurar a .env: Copiar o env.exemple e descomentar as entradas do banco de dados
logo após na pasta do projeto
1 - "composer install" 
2 - "php artisan migrate"
3 - "php artisan key:generate"
4 - "php artisan db:seed"  
4 - para rodar o projeto "php artisan serve"
5 - caso nao funcione tente "composer global require laravel/installer" e tente o item 4
