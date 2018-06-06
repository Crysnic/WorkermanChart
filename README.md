# WorkermanChart

Chart application that use workerman library

Run
```
composer install
php server/index.php start
```
## ToDo
1. Create command Send, for example:
```
{
    "Command":"Send",
    "To":"receiver",
    "Message":"Hello man!"
}
```
2. Load server info from DI
3. Refactoring Kernel
