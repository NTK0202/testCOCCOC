# Start
* Copy file .env.example => .env
* `docker-compose up -d --build`
* `docker-compose exec app sh`
  * composer install
  * php artisan key:generate
  * php artisan migrate
  * php artisan db:seed

## API Endpoints

### GET /ads

* Description: Get a list of ads
* Parameters:
  * `page`: optional, page number for pagination, default is 1
  * `sort_by`: optional, sort by “price” or “created_at", default is "created_at”
  * `sort_order`: optional, sort order “asc” or “desc’, default is “desc”
* Response:
  * `ads`: an array of ads objects
    * `id`: the ID of the ad
    * `title`: the title of the ad
    * `main_picture`: the URL of the main picture of the ad
    * `price`: the price of the ad

  * `pagination`: pagination info
    * `page`: current page number
    * `total_pages`: total number of pages
    * `total_items`: total number of items

### GET /ads/{id}

* Description: Get a specific ad by ID
* Parameters:
  * `fields`: optional, comma-separated list of fields to include in the response, default is "title price,main_picture"
* Response:
  * `id`: the ID of the ad
  * `title`: the title of the ad
  * `description`: the description of the ad
  * `main_picture`: the URL of the main picture of the ad
  * `pictures`: an array of URLs of all pictures of the ad
  * `price` the price of the ad

### POST /ads

* Description: Create a new ad
* Parameters:
  * `title`: required, the title of the ad, max 200 characters
  * `description`: optional, the description of the ad, max 1000 characters
  * `main_picture`: required, the URL of the main picture of the ad
  * `pictures`: optional, an array of URLs of all pictures of the ad, max 3 pictures
  * `price`: required, the price of the ad
* Response:
  * `id`: the ID of the created ad
  * `result`: "success" or "error"
