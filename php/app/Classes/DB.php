<?php
namespace Classes;

require_once '/var/www/html/pages/functions/functions.php';

class DB
{
    /** PDOオブジェクト、DB_setで呼び出す。
     *  @var object
     */
    private $pdo;

    /** このクラスで扱うテーブルの名前
     *  @var string
     */ 
    private $table_name;

    /** execメソッドで実行される、SQL文
     *  @var string
     */
    private $sql;

    /** execメソッド実行時にswitch文に渡される変数、実行される処理を決める　
     *  @var string
     */
    private $method;

    /** sqlを含んだ PDOオブジェクト、PDO::prepare()の返り値
     *  @var object 
     */
    private $stmt;

    /** execメソッド内で、PDO::execute()実行時に渡されるデータの配列
     *  @var array
     */
    private $data = [];

    /** トランザクションが実行されているかどうかを示す、実行した場合、１
     *  @var int
     */
    private $transaction = 0;

    /**
     * データベースのテーブルの名前、このクラスのインスタンス作成時に接続する
     * 
     * PDOでデータベースに接続するメソッド
     * DB_set()関数を使う
     * 
     * @param string 
     * 
     * @return void
     * 
     */
    public function __construct(string $table_name)
    {
        $this->pdo = DB_set();
        $this->table_name = $table_name;
    }

    /**
     * テーブルのデータを全部取得する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * execメソッドでPDO::fetchAll()で取得したデータを返す
     * 
     * @return string $this->sql, $this->method
     * 
     */
    public function findAll()
    {
        $this->sql = "SELECT * FROM $this->table_name";

        $this->method = 'findAll';
        return $this;
    }

    /**
     * データを1つ取得する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * execメソッドでPDO::fetch()で取得したデータを返す
     * 
     * @param int $record_id 取得したいデータベースの'id'
     * 
     * @return string $this->sql, $this->method
     * @return array $this->data[]
     * 
     */
    public function findId(int $record_id)
    {
        $this->sql = "SELECT * FROM $this->table_name WHERE id = ?";

        $this->data[] = $record_id;

        $this->method = 'findId';
        return $this;
    }

    /**
     * データを追加するメソッド
     * 
     * execメソッドを使用する必要、無し
     * 
     * @param array ['column_name' => 'value'] の配列
     * 
     * @return array  $this->data
     * @return string $this->sql, $this->method
     * 
     */
    public function insert(array $data_array)
    {
        foreach ($data_array as $key => $value) {

            $column_array[] = $key;

            $this->data[] = $value;

            $marks[] = '?';
        }

        $columns = implode(',', $column_array);
        $placeholders = implode(',', $marks);

        $this->sql = "INSERT INTO $this->table_name ($columns)
                VALUES($placeholders)";

        $this->method = 'insert';
        return $this;
    }

    /**
     * データをアップデートする事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * このメソッドの後に '->where' とし、whereメソッドと併用、可能
     *
     * @param array ['column_name' => 'value'] の配列
     * 
     * @return string $this->sql, $this->method
     * @return array $this->data[]
     * 
     */
    public function update(array $data_array)
    {
        foreach ($data_array as $key => $value) {
            $column_array[] = "$key = ?";
            $this->data[] = $value;
            
        }

        $columns = implode(',', $column_array);

        $this->sql = "UPDATE $this->table_name SET $columns ";

        $this->method = 'update';
        return $this;
    }

    /**
     * データを削除する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * このメソッドの後に '->where' とし、whereメソッドと併用、可能
     * 
     * @return string $this->sql, $this->method
     * 
     */
    public function delete()
    {
        $this->sql = "DELETE FROM $this->table_name";

        $this->method = 'delete';
        return $this;
    }

    /**
     * データを1つ取得する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * メソッドチェーンで 'where', 'orderBy' と併用、可能
     * execメソッドでPDO::fetch()で取得したデータを返す
     * 
     * @return string $this->sql, $this->method
     * 
     */
    public function selectOne()
    {
        $this->sql = "SELECT * FROM $this->table_name";

        $this->method = 'selectOne';
        return $this;
    }

    /**
     * データを複数取得する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * メソッドチェーンで 'where', 'orderBy', 'limit' と併用、可能
     * execメソッドでPDO::fetchAll()で取得したデータを返す
     * 
     * @return string $this->sql, $this->method
     * 
     */
    public function selectPlural()
    {
        $this->sql = "SELECT * FROM $this->table_name";

        $this->method = 'selectPlural';
        return $this;
    }

    /**
     * 指定したカラムのデータ数を取得する事を指定するメソッド
     * 
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * 
     * @return string $this->sql, $this->method
     * 
     */
    public function count($column)
    {
        $this->sql = "SELECT COUNT($column) FROM $this->table_name";

        $this->method = 'count';
        return $this;
    } 

    /**
     * 取得するデータのカラムと値を指定するメソッド
     * 
     * 他のメソッド('update', 'delete', 'selectOne', 'selectPlural')と併用する必要がある
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * 
     * @param string $column テーブルのカラム名 
     * @param string $sign 算術演算子を期待、e.g. '='
     * @param mixed $value
     * 
     * @return string $this->sql
     * 
     */
    public function where($column, $sign, $value)
    {
        $this->sql .= " WHERE $column $sign '$value'";
        return $this;
    }

    /**
     * データを昇順、降順で取得するか指定するメソッド
     * 
     * 他のメソッド('findAll', 'selectPlural')と併用する必要がある
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * 
     * @param string $column_name
     * @param string $asc_desc 'ASC', 'DESC'を期待
     * 
     * @return string $this->sql
     * 
     */
    public function orderBy(string $column_name,string $asc_desc = 'ASC')
    {
        $this->sql .= " ORDER BY $column_name $asc_desc";
        return $this;
    }

    /**
     * データをいくつ取得するか指定するメソッド
     * 
     * 他のメソッド('findAll', 'selectPlural')と併用する必要がある
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * 
     * @param int $from     いくつ目のデータから取得するか
     * @param int $how_many いくつのデータを取得するか
     * 
     * @return string $this->sql
     * 
     */
    public function limit(int $from, int $how_many)
    {
        $this->sql .= " LIMIT $from, $how_many";
        return $this;
    }

    /**
     * 取得するデータのカラムを指定するメソッド
     * 
     * 他のメソッド('findAll', 'findId', 'selectOne', 'selectPlural')と併用する必要がある
     * PDO::execute()を実行する為に、execメソッドをこのメソッドのあとに使用する必要がある
     * 
     * @param array $columns 取得したいデータのカラム名
     * 
     * @return string $this->sql
     * 
     */
    public function value(array $columns)
    {
        $string = implode(',', $columns);

        $this->sql = str_replace('*', $string, $this->sql);
        return $this;
    }

    /**
     * トランザクション実行
     * 
     * 使用例：
     * $pdo = new DB('table_name');
     * $pdo->beginTransaction();
     * 
     * $pdo->delete()
     *     ->exec();
     * 
     * $pdo->rollback();
     * 
     * @return object $this->pdo
     * @return int $this->transaction
     * 
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
        $this->transaction = 1;
        return $this;
    }

    /**
     * ロールバック
     * 
     * @return void
     */
    public function rollBack()
    {
        $this->pdo->rollBack();
        $this->pdo = null;
    }

    /**
     * コミット
     * 
     * @return void
     */
    public function commit()
    {
        $this->pdo->commit();
        $this->pdo = null;
    }

    /**
     * このメソッドの前に使用されたメソッドに応じて、PDO::prepare(),execute()を実行するメソッド
     * 
     * 'findAll', 'findId', 'selectOne', selectPlural', 'update', 'delete'
     * の後に使用する必要がある
     * 
     * @return array 'findAll', 'selectPlural'
     * @return array 'findId', 'selectOne'
     * @return void 'insert', 'upaate', 'delete'
     * @return int 'count'
     * 
     */
    public function exec()
    {
        switch($this->method) {
            case 'findAll':
            case 'selectPlural':
                $this->stmt = $this->pdo->prepare($this->sql);
                $this->stmt->execute();

                $records = $this->stmt->fetchAll();

                $this->stmt = null;

                if ($this->transaction == 0) {
                    
                    $this->pdo = null;
                }

                return $records;
                break;
            
            case 'findId':
            case 'selectOne':
                $this->stmt = $this->pdo->prepare($this->sql);
                $this->stmt->execute($this->data);

                $record = $this->stmt->fetch();

                $this->stmt = null;

                if ($this->transaction == 0) {
                    
                    $this->pdo = null;
                }

                return $record;
                break;

            case 'insert':
            case 'update':
                $this->stmt = $this->pdo->prepare($this->sql);
                $this->stmt->execute($this->data);

                $this->stmt = null;

                if ($this->transaction == 0) {

                    $this->pdo = null;
                }
                break;

            case 'delete':
                $this->stmt = $this->pdo->prepare($this->sql);
                $this->stmt->execute();

                $this->stmt = null;

                if ($this->transaction == 0) {
                    
                    $this->pdo = null;
                }
                break;

            case 'count':
                $this->stmt = $this->pdo->prepare($this->sql);
                $this->stmt->execute();

                $num = $this->stmt->fetchColumn();

                $this->stmt = null;

                if ($this->transaction == 0) {

                    $this->pdo = null;
                }

                return $num;
                break;
        }
    }
}
