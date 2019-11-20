<?php
  define("CRASE", "`");
  require_once __DIR__ . "/../../configs/relations.config.php";
  class query extends relations {
    //Variavél global de bloquieo
    protected $block = false;
    protected $conn = null;
    protected $key = null;

    //var do query atual
    protected $where = "";
    protected $limit = "";
    protected $order = [];
    protected $group = "";

    protected $pColls = array();
    protected $opreration = "";
    protected $readLiteral = null;

    //Esecução
    public $sql = "";
    protected $link = "";
    protected $forceInsert = false;
    protected $arrayFixed = false;
    protected $lastId = "";
    public    $erros = array();

    //Outras
    protected $table = "";
    protected $colls = array();
    public    $pk = false;
    protected $fk = false;
    protected $dist = false;
    protected $viewCollsFk = array();
    protected $setDbFk = array();
    protected $getFkVar = true;

    protected $loadPkExcepion = array();
    protected $fxOut = array();

    function addColls($name, $info){
      $this->colls[$name] = (array)$info;
    }

    function setPk($col){
      $this->pk = $col;
    }

    function groupColls($inColls, &$outColls = "", &$outValues = ""){
      if(is_array($outColls) && is_array($outValues)) $type = 2;
      else if(is_array($outColls)) $type = 1;
      else return false;

      if(!is_array($inColls)) return false;

      foreach ($inColls as $key => $val) {

        if($this->verify($key)){
          if($type == 1){
            $simb = " = ";
            $outColls[] = CRASE . $key . CRASE . $simb . $this->verifytype($key, $val);
          }else{
            $outColls[] = CRASE . $key . CRASE;
            $outValues[] = $this->verifytype($key, $val);
          }
        }

      }
    }
    public function removeRelation($key){
      if( isset( $this->colls[$key]['Function'] ) ){
        unset($this->colls[$key]['Function']);
      }
      return $this;
    }

    protected function load_relations(){
      if( isset($this->tables[$this->table]) ) {
        foreach ($this->colls as $coll => &$data) {
          if(isset($this->tables[$this->table][$coll])) {
            $data['Function'] = $this->tables[$this->table][$coll];
          }
        }
      }
    }

    function debug(){
      $this->mountSql();
      return $this->sql;
    }


    private function mounteSmartSearch($smartSearch){
      if( isset($smartSearch['table']) && isset($smartSearch['find']) && isset($smartSearch['where']) ){
        if(is_array($smartSearch['where']))
          $smartSearch['where'] = $this->mounteSmartSearch($smartSearch['where']);

        if( !empty($smartSearch['where'])){
          $query = "
            SELECT `{$smartSearch['find']}`
            FROM `{$smartSearch['table']}`
            WHERE {$smartSearch['where']}
          ";
          $buscas = [];
          $nValue = $this->conn->query($query);

          while ( $tb = $nValue->fetch_array() ) {
            $out = isset($smartSearch['out']) ? $smartSearch['out'] : $smartSearch['find'];
            $buscas[] = "`{$out}` = '{$tb[0]}'";
          }

          if( !empty( $buscas )) return " ( " . implode(" or ", $buscas) . " ) ";
          else return "";
        }else{
          return "";
        }
      }else{
        return "";
      }
    }

    private function simplifi_sfilter($search){
      foreach ($search as &$value) {
        if(is_array($value)){
          $value = $this->mounteSmartSearch($value) ;
        }
      }
      return implode(" and ", $search);
    }

    function sTable($header = null, $cols = "*"){
      $consulta = $this->read($cols);
      $num_rows = $this->conn->query("SELECT COUNT(*) from {$this->table}");
      $num_rows = $num_rows->fetch_array();
      $num_rows = $num_rows[0];

      $after_order = array();

      if($header != null) {

        if(isset($header['order'])){
          foreach ($header['order'] as $value) {
            if( strpos($value['data'], '.') !== false || is_array($value['data']) ){
              $after_order[] = $value;
            }else{
              $val = is_array($value['data']) ? $value['data'][0] : $value['data'];
              $consulta->order($val, $value['sent']);
            }
          }
        }

        if( isset($header['pager']) ){
          if( empty($after_order) ){
            $inicio = $header['pager']['page'] * $header['pager']['qnt'];
            if($inicio != 0) $inicio--;
            $consulta->limit($inicio, $header['pager']['qnt']);
          }
        }
        if( isset($header['filter']) && $header['filter'] != ""){

          if( !is_array($header['filter'])) $filter = $header['filter'];
          else $filter = $this->simplifi_sfilter( $header['filter'] );

          $consulta->where($filter);
          $clone_consulta = $consulta;
          $table = $consulta->fixed()->toArray();

          // echo $consulta->sql;


          $num_rows = $this->conn->query(
            "SELECT COUNT(*) from {$this->table} WHERE {$filter} ");

          if( $num_rows != false ){
            $num_rows = $num_rows->fetch_array();
            $num_rows = $num_rows[0];
          }else{
            $num_rows = 0;
          }


          // echo "SELECT COUNT(*) from {$this->table} WHERE {$filter} ";

        }else{
          $table = $consulta->fixed()->toArray();
        }
      }else{
        $table = $consulta->fixed()->toArray();
      }

      // print_r($after_order);
      // echo $consulta->sql;
      return array(
        "num_rows" => $num_rows,
        // "table" => array(),
        "table" => $this->after_order( $table,  $after_order, $header['pager'])
      );

    }
    protected function get_data($nav, $array, $x = 0 ){
      $navi = explode(".", $nav);
      if( isset($array[$navi[$x]]) && !is_array($array[$navi[$x]]) )
        return $array[$navi[$x]];
      else if( isset($array[$navi[$x]]) && is_array($array[$navi[$x]]) )
        return $this->get_data($nav, $array[$navi[$x]], ++$x);
      else return 'undefined';
    }

    protected function after_pager($table, $pager){
      $pTb = array();
      $page = isset($pager['page']) ? $pager['page'] : 0;
      $qnt = isset($pager['qnt']) ? $pager['qnt'] : 0;
      for($x=$page; $x < $qnt; $x++){
        if( isset($table[$x]) ) $pTb[] = $table[$x];
      }
      return $pTb;
    }

    protected function after_order( $table, $order, $pager ){
      if(!empty($order)){
        usort($table, function($atual, $proxima) use ($order){
          $resp = true;
          foreach ($order as $value) {
            $val_atual = $this->get_data($value['data'], $atual);
            $val_proxima = $this->get_data($value['data'], $proxima);
            if($value['sent'] == "ASC") $resp = $resp && $val_atual > $val_proxima;
            else $resp = $resp && $val_atual < $val_proxima;
          }
          return $resp;
        });

      }
      return  $this->after_pager( $table, $pager );

    }

    //Poe a $conexão a disposição
    function __construct($mysql, $table){
      if(!empty($table)) {
        $this->table = $table;
        if(isset($mysql['conn']) && isset($mysql['key']) ) {
          if( is_object( $mysql['conn'] ) && !empty($mysql['key']) ){
            $this->conn = $mysql['conn'];
            $this->key = $mysql['key'];

            // Montando a grade das colunas
            $info_table = $this->conn->query("DESC {$table}");
            if($info_table){
              while($iTab =  $info_table->fetch_object()){
                //Definições inicias
                $Field = $iTab->Field;
                unset($iTab->Field);
                $aTab = (array)$iTab;

                //Processando o tipo
                $type = str_replace(")", "", $aTab["Type"]);
                if(strripos($type, "(") !== false){
                  $type = explode("(", $type);
                  $aTab["Type"] = $type[0];
                  $aTab["Length"] = $type[1];
                }else{
                  $aTab["Length"] = 0;
                }

                //Setando configurações
                $this->addColls($Field, $aTab);
                if($iTab->Key == "PRI") $this->setPk($Field);
                // if($iTab->Key == "MUL") $this->addFk($Field);

              }
              $this->load_relations();
            }else{
              $this->erros[] = "A tabela {$table} não existe no banco de dados!";
              $this->block = true;
            }
          }else{
            $this->block = true;
          }
        }else{
          $this->block = true;
        }
      }
    }

    function clearVars(){
      $this->where = "";
      $this->limit = "";
      $this->order = "";
      $this->group = "";
      $this->pColls = array();
      $this->opreration = "";
      $this->sql = "";
      $this->link = "";
      $this->forceInsert = false;
      $this->arrayFixed = false;
      $this->lastId = "";
      $this->viewCollsFk = array();

    }

    function prepare(&$data){
      foreach($data as $key => $val){
        if(!$this->verify($key)){
          unset($data[$key]);
        }
      }
      return true;
    }

    function genId($step = 0){
      return md5( microtime() . rand() . $this->key . $step);
    }

    // function nextId($step = 1){
    //  if($this->colls[$this->pk]["Type"] == "varchar"){
    //    $newId = $this->genId($step);
    //  }
    //  if($this->exists($this->pk, $newId)){
    //    $step++;
    //    $this->lastId = $this->nextId($step++);
    //    return $this->lastId;
    //  }else{
    //    $this->lastId = $newId;
    //    return $newId;
    //  }

    // }



    function where($string, $val = ""){
      if(is_array($val)){
        $this->erros[] = "O argumento \$val não pode ser array!";
        return $this;
      }
      if(is_string($string) && is_string($val)){
        $simb = strrpos($val, "%") === false ? " = " : " like ";
        if($this->verify($string)){
          $this->where = CRASE . $string . CRASE . $simb . $this->verifytype($string, $val);
        }else{
          $this->where = $string;
        }

      }else if(is_array($string)){
        $mont = array();
        foreach ($string as $coll => $val) {
          if($this->verify($coll)){
            $simb = strrpos($val, "%") === false ? " = " : " like ";
            $mont[] = CRASE . $coll . CRASE . $simb . $this->verifytype($coll, $val);
          }
        }
        $this->where = implode(" and ", $mont);
      }

      return $this;
    }
    function limit($init, $length = false){
      $this->limit = $init;
      if($length !== false) $this->limit .= ", " . $length;
      return $this;
    }
    function order($string, $type = "ASC"){
      $this->order[] = CRASE . $string . CRASE . " " . strtoupper($type);
      return $this;
    }
    function group($string){
      $this->group = CRASE . $string . CRASE;
      return $this;
    }

    function fixed(){
      $this->arrayFixed = true;
      return $this;
    }

    function lastId(){
      return $this->lastId;
    }

    private function verifytype($key, $val){
        //funções msql
        if($val == "now()") return $val;
        $type = strtolower($this->colls[$key]["Type"]);

        if($type == "smallint" || $type == "mediumint" || $type == "int" || $type == "bigint"){
          return !empty($val) ? $val : 0 ;
        }else{
          return "'" . $val . "'";
        }
    }

    private function verify($data, $log = true){
      $countError = 0;
      if(is_array($data)){
        //o esperado é receber uma array do tipo array("coll" => "value")
        foreach ($data as $key => $value) {
          $key = str_replace("!", "", $key);
          if(!isset($this->colls[$key])){
            if($log == true)
              $this->erros[] = "A coluna $key não existe no banco de dados!";
            $countError++;
          }
        }

      }else{
        if(!isset($this->colls[$data])) $countError++;
      }

      return $countError > 0 ? false : true;

    }

    function exists($colls = "PK", $val = ""){
      $this->clearVars();
      if(is_array($colls)){
        $resp = $this
          ->read($this->pk)
          ->where($colls)
          ->limit(1)
          ->toArray();
      }else{
        if($this->verify($colls)){
          $resp = $this
            ->read($colls)
            ->where(CRASE . $colls . CRASE . " = " . $this->verifytype($colls, $val))
            ->limit(1)
            ->toArray();
        }else{
          $resp = $this
            ->read($this->pk)
            ->where(CRASE . $this->pk . CRASE . " = " . $this->verifytype($this->pk, $colls))
            ->limit(1)
            ->toArray();
        }
      }

      if(!empty($resp)){
        return true;
      }else{
        return false;
      }
    }

    private function all_colls(){
      $all_colls = array();
      foreach ($this->colls as $key => $value) {
        $all_colls[] = $key;
      }
      return $all_colls;
    }

    // RDS ===============
    function read($colls = "*", $literal=false){
      $this->clearVars();
      $this->opreration = "SELECT";

      if($literal){
        $this->readLiteral = $colls;
        return $this;
      }

      if($colls == "*" || $colls == " * "){
        $this->pColls = $colls;
      }else{
        $preColls = str_replace(" ", "", $colls);
        $preColls = explode(",", $preColls);

        if(in_array("*", $preColls)){
          $allColls = $this->all_colls();
          foreach ($allColls as $key => $value) {
            if(in_array("-{$value}", $preColls)){
              unset($allColls[$key]);
            }else if(in_array("!{$value}", $preColls)){
              $this->loadPkExcepion[] = $value;
            }
          }
        }else{
          $allColls = array();
          foreach ($preColls as $value) {
            if( strpos( $value, '!' ) !== false ){
              $value = str_replace('!', '', $value);
              if($this->verify($value)){
                $this->loadPkExcepion[] = $value;
                $allColls[] = CRASE . $value . CRASE;
              }
            }else{
              if($this->verify($value)){
                $allColls[] = CRASE . $value . CRASE;
              }
            }
          }
        }
        $this->pColls = implode(", ", $allColls);
      }
      return $this;
    }

    function save($colls, $val = ""){
      $this->clearVars();
      if(is_array($colls)){

        //Verifica o status
        if(isset($colls[$this->pk]) && !empty($colls[$this->pk])){


          if($this->exists($colls[$this->pk])) {
            $this->clearVars();
            $this->opreration = "UPDATE";
            $this->lastId = $colls[$this->pk];

            $this->where = CRASE . $this->pk . CRASE . " = " . $this->verifytype($this->pk, $colls[$this->pk]);
            unset($colls[$this->pk]);

          }else{
            $this->clearVars();
            $this->lastId = $colls[$this->pk];
            $this->opreration = "INSERT";
          }

        }else{
          $this->clearVars();
          if( $this->colls[$this->pk]["Type"] == "varchar"){
            $colls[$this->pk] = $this->lastId = $this->genId(rand());
          }
          $this->opreration = "INSERT";

        }
        //print_r($colls);
        $this->processorIn($colls);
        $this->prepare($colls);
        $this->pColls = $colls;

      }else{
        if($this->verify($colls)){
          $gardian = array();
          $gardian[$colls] = $val;
          $this->processorIn( $gardian );
          if(isset($gardian[$colls])) $this->pColls[$colls] = $gardian[$colls];
          $this->opreration = "INSERT";
        }
      }

      return $this;
    }



    function delete($data, $val = ""){
      $this->clearVars();
      $this->opreration = "DELETE";
      if(is_array($data)){
        $this->prepare($data);
        if(isset($data[$this->pk])){
          $this->where = CRASE . $this->pk . CRASE . " = " . $data[$this->pk];
          $this->limit = 1;
        }else{
          $colls = array();
          $this->groupColls($data, $colls);
          $this->where = implode(" and ", $colls);
        }
      }else{
        if($this->verify($data)) {
          $this->where = CRASE . $data . CRASE . " = " . $this->verifytype($data, $val);
        }else if(!$this->verify($data) && empty($val) ){
          $this->where = CRASE . $this->pk . CRASE . " = " . $this->verifytype($this->pk, $data);
        }
      }
      return $this;
    }



    function mountSql($action = false){

      if($this->where != "" && $this->opreration == "INSERT"){
        $key = "UPDATE";
      } else {
        $key = $this->opreration;
      }


      switch ($key) {
        case 'SELECT':
          $this->sql = $key;
          if($this->readLiteral != null) $this->sql .= " {$this->readLiteral} ";
          else $this->sql .= " " . $this->pColls;
          $this->sql .= " FROM " . $this->table;
          if($this->where !== "")
            $this->sql .= " WHERE " . $this->where;
          if($this->group !== "")
            $this->sql .= " GROUP BY " . $this->group;
          if( !empty($this->order) ){
            $this->sql .= " ORDER BY " . implode(", ", $this->order);
          }
          if($this->limit !== "")
            $this->sql .= " LIMIT " . $this->limit;

        break;
        case 'UPDATE':
          $this->sql = $key . " " . CRASE . $this->table . CRASE;
          $groupColls = array();
          $this->groupColls($this->pColls, $groupColls);
          $this->sql .= " SET " . implode(", ", $groupColls);

          if($this->where !== "")
            $this->sql .= " WHERE " . $this->where;

          if($this->limit !== "")
            $this->sql .= " LIMIT " . $this->limit;

        break;
        case 'INSERT':

          $this->sql = $key;
          $this->sql .= " INTO " . CRASE . $this->table . CRASE;
          //Agrupando colunas
          $groupColls = array();
          $groupValues = array();
          $this->groupColls($this->pColls, $groupColls, $groupValues);

          $this->sql .= " ( " . implode(", ", $groupColls) . " ) ";
          $this->sql .= " VALUES ( " . implode(", ", $groupValues) . " ) ";

        break;
        case 'DELETE':
          $this->sql = $key;
          $this->sql .= " FROM " . $this->table;
          if($this->where !== "")
            $this->sql .= " WHERE " . $this->where;
          if($this->order !== "")
            $this->sql .= " ORDER BY " . $this->order;
          if($this->limit !== "")
            $this->sql .= " LIMIT " . $this->limit;
          else
            $this->sql .= " LIMIT 1" ;


        break;

      }
      // print_r($this->sql);
      return $this;
    }


    public function executeSql($sql = ""){

        //Montando sql
        if($sql == ""){
          try{
            $this->mountSql();
            $eSql = $this->sql;
          }catch(Exception $e) {
            response::error('Conversão inesperada de Array');
            $this->erros[] = 'Conversão inesperada de Array';
            return false;
          }
        }else{
          $eSql = $sql;
        }

        // echo $eSql . '<br>';

        //executando
        if(!empty($eSql)){
          $this->link = $this->conn->query($eSql);
          if($this->link){
            if( $this->colls[$this->pk]["Type"] != "varchar"){
              $this->lastId = $this->conn->insert_id;
            }
            return $this;
          }else{
            $this->erros[] = $eSql;
            $this->erros[] = "Erro ao executar o query acima!";
            return $this;
          }
        }else{
          $this->erros[] = "O campo sql não está montado!";
          return false;
        }
    }

    function exec($log = false){
      $this->executeSql();
      if($this->link === false){
        if($log == false){
          return false;
        }else{
          return $this->sql;
        }
      }else{
        return true;
      }
    }

    function processorIn(&$data){
      foreach ($data as $coll => &$val) {
        if( isset($this->colls[$coll]) ){
          if( isset($this->colls[$coll]['Function']) ){
            $type = $this->colls[$coll]['Function'];

            //Funções de automanipulação
            if($type == 'json') {
              if(is_array($val)) $val = json_encode($val);
              else if($val == '') $val = json_encode(array());
            }else if($type == 'base64') {
              $val = base64_encode($val);

            }elseif ( $type == 'base64-js') {
              if(is_array($val)) $val = json_encode( $val );
              else if($val == '') $val = json_encode(array());
              $val = base64_encode( $val );

            }else {
              if(strpos($type, '.') != false && is_array($val)){
                $dchild = explode('.', $type);
                $restore_mysql = array(
                  "conn" => $this->conn,
                  "key" => $this->key
                );
                $child_table = new query($restore_mysql, $dchild[0]);
                $child_table->save( $val )->exec();

                if( isset($val[$child_table->pk]) ){
                  $val = $val[$child_table->pk];
                }else{
                  $val = $child_table->lastId;
                }

              }
            }

          }

        }


      }
    }

    //processadores
    protected function simpleSql($link){
      $result = array();
      while ($tab = $link->fetch_object()) {
        $result[] = (array)$tab;
      }

      if(count($result) == 1) return $result[0];
      else return $result;
    }

    public function addOutFx($target, $fx){
      $this->fxOut[$target] = $fx;
      return $this;
    }

    protected function generation(){
      if($this->link !== false){
        $resp = array();
        while ($tab = $this->link->fetch_object()) {
          foreach ($tab as $coll => &$val) {
            if( isset($this->colls[$coll]) ){
              if( isset($this->colls[$coll]['Function']) ){
                $type = $this->colls[$coll]['Function'];

                if($type == 'json'){
                  if( !empty($val) && $val != "" ) $val = (array)json_decode($val);
                  else $val = json_decode("[]");
                }elseif ( $type == 'base64') {
                  if( !empty($val) && $val != "" ) $val = base64_decode( $val );

                }elseif ( $type == 'base64-js') {
                  if(!empty($val) && $val != ""){
                    $val = base64_decode( $val );
                    $val = (array)json_decode( $val );
                  }else $val = json_decode( "[]" );

                }else{
                  if(!empty($val) && $val != ""){
                    if( strpos($type, '.') != false && !in_array($coll, $this->loadPkExcepion) ){
                      $dchild = explode('.', $type);
                      $restore_mysql = array(
                        "conn" => $this->conn,
                        "key" => $this->key
                      );

                      $child_table = new query($restore_mysql, $dchild[0]);
                      $val = (array)$child_table
                        ->read()
                        ->where($dchild[1], $val)
                        ->toArray();
                    }
                  }
                }

              }else if($this->colls[$coll]['Type'] == "tinyint"){
                if($val == 0) $val = false;
                else if($val == 1) $val = true;
              }else if(isset($this->fxOut[$coll])){
                try{
                  $val = $this->fxOut[$coll](array(
                    "coll" => $coll,
                    "val" => $val,
                    "line" => $tab
                  ));
                }catch(Exception $e){
                  response::error($e->getMesage());
                }
              }

            }
          }
          $resp[] = (array)$tab;
        }
        return $resp;
      }
    }

    function toArray(){
      $this->executeSql();
      $resp = $this->generation();
      if($this->readLiteral != null) return $resp;

      if(count($resp) == 1 && $this->arrayFixed == false){
        if(count($resp[0]) == 1){
          $coll = str_replace(CRASE, "", $this->pColls);
          if( empty($coll) ) return $resp[0];
          else return $resp[0][$coll];
        }else{
          return $resp[0];
        }

      }else{
        return $resp;
      }
    }

    function toJson(){
      $resp = $this->toArray();
      $search = array('"true"', '"false"');
      $replace = array('true', 'false');
      return str_replace($search , $replace, json_encode($resp));
    }


    function __toString(){
      return $this->toJson();
    }

  }
