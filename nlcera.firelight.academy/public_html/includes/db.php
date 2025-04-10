<?php
/**
 * Database Connection File
 * Establishes connection to the MySQL database
 * 
 * Place this file in: /includes/db.php
 */

require_once 'config.php';

/**
 * Get Database Connection
 * Returns a PDO connection to the database
 *
 * @return PDO Database connection object
 */
function get_db_connection() {
    static $pdo;
    
    if (!isset($pdo)) {
        try {
            // Create PDO connection
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Database Connection Failed: " . $e->getMessage());
            } else {
                die("Database Connection Failed. Please contact administrator.");
            }
        }
    }
    
    return $pdo;
}

/**
 * Execute Query
 * Executes an SQL query with optional parameters
 *
 * @param string $query SQL query to execute
 * @param array $params Optional parameters for the query
 * @return PDOStatement The query result
 */
function db_query($query, $params = []) {
    $pdo = get_db_connection();
    
    try {
        $statement = $pdo->prepare($query);
        $statement->execute($params);
        return $statement;
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Query Failed: " . $e->getMessage());
        } else {
            die("Database Error. Please contact administrator.");
        }
    }
}

/**
 * Get a single record
 * 
 * @param string $query SQL query to execute
 * @param array $params Parameters for the query
 * @return array|null The first row of results or null
 */
function db_get_row($query, $params = []) {
    $statement = db_query($query, $params);
    return $statement->fetch();
}

/**
 * Get multiple records
 * 
 * @param string $query SQL query to execute
 * @param array $params Parameters for the query
 * @return array The results
 */
function db_get_rows($query, $params = []) {
    $statement = db_query($query, $params);
    return $statement->fetchAll();
}

/**
 * Insert a record and return the ID
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int The last inserted ID
 */
function db_insert($table, $data) {
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), '?');
    
    $query = "INSERT INTO $table 
              (" . implode(', ', $columns) . ") 
              VALUES 
              (" . implode(', ', $placeholders) . ")";
              
    $pdo = get_db_connection();
    $statement = $pdo->prepare($query);
    $statement->execute(array_values($data));
    
    return $pdo->lastInsertId();
}

/**
 * Update a record
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @param string $where Where clause (e.g. "id = ?")
 * @param array $params Parameters for the where clause
 * @return int Number of rows affected
 */
function db_update($table, $data, $where, $params = []) {
    $set = [];
    foreach ($data as $column => $value) {
        $set[] = "$column = ?";
    }
    
    $query = "UPDATE $table 
              SET " . implode(', ', $set) . " 
              WHERE $where";
              
    $statement = db_query($query, array_merge(array_values($data), $params));
    
    return $statement->rowCount();
}

/**
 * Delete a record
 * 
 * @param string $table Table name
 * @param string $where Where clause (e.g. "id = ?")
 * @param array $params Parameters for the where clause
 * @return int Number of rows affected
 */
function db_delete($table, $where, $params = []) {
    $query = "DELETE FROM $table WHERE $where";
    $statement = db_query($query, $params);
    
    return $statement->rowCount();
}
?>