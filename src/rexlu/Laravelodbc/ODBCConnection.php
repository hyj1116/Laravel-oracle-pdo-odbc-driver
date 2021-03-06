<?php namespace rexlu\Laravelodbc;

use Illuminate\Database\Connection;
use PDO;

class ODBCConnection extends Connection {

	/**
	 * Get the default query grammar instance.
	 *
	 * @return Illuminate\Database\Query\Grammars\Grammars\Grammar
	 */
	protected function getDefaultQueryGrammar()
	{
        return $this->withTablePrefix(new ODBCQueryGrammar);
	}

	/**
	 * Get the default schema grammar instance.
	 *
	 * @return Illuminate\Database\Schema\Grammars\Grammar
	 */
	protected function getDefaultSchemaGrammar()
	{
        return $this->withTablePrefix(new ODBCSchemaGrammar);
	}

    /**
     * @param string $format
     * @return $this
     */
    public function setDateFormat($format = 'YYYY-MM-DD HH24:MI:SS')
    {
        $this->statement("alter session set NLS_DATE_FORMAT = '$format'");
        $this->statement("alter session set NLS_TIMESTAMP_FORMAT = '$format'");
        return $this;
    }

    public function beginTransaction()
    {
        parent::beginTransaction();

        if ($this->transactions == 1)
        {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        }
    }

    public function commit()
    {
        if ($this->transactions == 1) {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        }
        parent::commit();
    }

    public function rollBack()
    {
        if ($this->transactions == 1) {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        }
        parent::rollBack();
    }
}
