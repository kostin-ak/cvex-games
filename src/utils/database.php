<?php

include_once __DIR__."/../configs/config.php";

final class Connection
{
    /**
     * Connection
     * тип @var
     */
    private static ?Connection $conn = null;

    /**
     * Подключение к базе данных и возврат экземпляра объекта \PDO
     * @return PDO
     * @throws Exception
     */
    public function connect()
    {

        // чтение параметров в файле конфигурации ini
        $params = parse_ini_file(ROOT.'configs/database.ini');
        if ($params === false) {
            throw new Exception("Error reading database configuration file");
        }

        // подключение к базе данных postgresql
        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'],
            $params['port'],
            $params['database'],
            $params['user'],
            $params['password']
        );

        $pdo = new PDO($conStr);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get()
    {
        if (null === Connection::$conn) {
            Connection::$conn = new self();
        }

        return Connection::$conn;
    }

    protected function __construct()
    {

    }
}
?>