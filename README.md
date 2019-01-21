# Happiness monitor application

## Endpoints:

  * [POST] /api/v1/happiness-logs - create happiness log based on user's data

  * [GET] /api/v1/happiness-logs - get list of logs

  * [GET] /api/v1/happiness-logs/export - export list of logs to file

  * [GET] /api/v1/happiness-logs/import - import data from url and save in db
 
## Things to improve:

  * code refactor:
      * move operations on db to repository
      * move importing from url to additional service
      * move exporting to file to additional service
      * inject services (like entity manager, repository, serializer, etc.) into controller

  * return data from CSV as a downloadable file

  * add validation
  
  * handle exceptions
  
## TIP

Data in https://s3-eu-west-1.amazonaws.com/novemberfive-serverside/data.json contains ununique id, but the log's dates for those records are unique. That's why I saved that field as an external_id id database. 