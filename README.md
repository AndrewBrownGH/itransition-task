Itransition task

### ParseCsv ###

I used the library [ParseCsv](https://github.com/parsecsv/parsecsv-for-php#parsecsv). This library allows you to read CSV data in a convenient way. 

### Migrations ###

I created 2 migrations: the first migration creates the table, the second migration add 2 new columns: stock and cost. 

### Start the process ###

If you want to run the import process, you should use the command: `php app/console import:csv *path to your csv file*`. For example, `php app/console import: csv /home/andrey/Загрузки/stock.csv`

### Test mode ###
If you want to run the import process in test mode, you should use the command: `php app/console import:csv *path to your csv file* —test`. For example, `php app/console import:csv /home/andrey/Загрузки/stock.csv —test`
