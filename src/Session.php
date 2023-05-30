<?php

namespace Perfluence;

use PDO;
use Perfluence\db\Connection;

class Session
{
    protected $connection;
    private $table = "Session";

    public function __construct()
    {
        $connection = new Connection();
        $this->connection = $connection->getConnection();
    }

    public function randomDate() {
        $start = mktime(0,0,0,1,1,2010);
        $end  = time();
        $randomStamp = rand($start, $end);
        return date('Y-m-d H:i:s', $randomStamp);
    }

    /**
     * Вызывается только 1 раз для заполнения пустой таблицы
     * Можно не использовать, тк в архиве есть дамп с заполненной таблицей
     * @return void
     */
    public function setData() {
        for ($user_id = 1; $user_id <= 1000000; $user_id++) {
            $dateLogin = $this->randomDate();
            $logoutStamp = strtotime($dateLogin) + 3600;
            $dateLogout = date("Y-m-d H:i:s", $logoutStamp);
            $sql = "INSERT INTO {$this->table} (user_id, login_time, logout_time) 
                    VALUES (:user_id, :login_time, :logout_time)";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(":user_id", $user_id);
            $statement->bindValue(":login_time", $dateLogin);
            $statement->bindValue(":logout_time", $dateLogout);
            $statement->execute();
        }
    }

    /**
     * @param string $date Для простоты дату передаем строкой Y-m-d или Y-m-d H:i:s
     * @return void
     */
    public function getData(string $date) {
        $start = time();
        $datetime = new \DateTime($date, new \DateTimeZone("Europe/Moscow"));

        $startDayData = getdate($datetime->format("U"));
        $startDay = new \DateTime(
            date(
                "Y-m-d H:i:s",
                mktime(0, 0, 0, $startDayData["mon"], $startDayData["mday"], $startDayData["year"])
            ),
            new \DateTimeZone("Europe/Moscow")
        );
        $nextDayData = getdate($datetime->modify("+1 day")->format("U"));

        $nextDay = new \DateTime(
            date(
                "Y-m-d H:i:s",
                mktime(0, 0, 0, $nextDayData["mon"], $nextDayData["mday"], $nextDayData["year"])
            ),
            new \DateTimeZone("Europe/Moscow")
        );

        // Сортируем по кол-ву, чтобы первой строкой получить максимальное кол-во
        $sql = "SELECT HOUR(login_time) as hour, COUNT(*) as count FROM {$this->table} WHERE
        (login_time BETWEEN :start AND :end)
        AND (logout_time BETWEEN login_time AND login_time + INTERVAL 1 HOUR)
        GROUP BY HOUR(login_time)
        ORDER BY COUNT(*) DESC";

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            "start" => $startDay->format("Y-m-d"),
            "end"   => $nextDay->format("Y-m-d")
        ]);

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        print "Наибольшее кол-во посещений (" . $data[0]['count'] . ") с " .
            $data[0]['hour'] + 1 . ":00 до " . $data[0]['hour'] + 2 . ":00\n";
        $end = time();

        print "Время выполнения: " . $end - $start . "s\n\n";
    }
}