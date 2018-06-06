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
2. Add validation for Action Send
3. Add Wrapper for users array
4. Add Facade for Action Send
5. Load server info from DI
6. Refactoring Kernel
