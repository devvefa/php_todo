<?php

class TodoList
{
    private $todoDbName;
    private $todoItemList;      //arr
    private $db;

    public function __construct(string $todoDbName)
    {
        $this->todoDbName=$todoDbName;
        $config=new Dbconfig($todoDbName);
        $this->db=$config->getDbFile();
    }

    public function getTodoes(): array
    {
        $this->todoItemList=json_decode(file_get_contents($this->db));
        return $this->todoItemList;

    }
    public function add(){
        $task=$_POST['task'];
        $note=$_POST['note'];
        $priority=$_POST['priority'];
        $status="";//"$_POST['status'];


        date_default_timezone_set("Europe/Istanbul");
        $createAt=date("Y-m-d H:i:s");


        if (!empty($task)){
            $this->todoItemList[]= array(
                'task'=>$task,
                'note'=>$note,
                'priority'=>$priority,
                'status'=>$status,
                'dateTime'=>$createAt,
                );



            $this->save();
        }
    }


    public function findById(int $id){

        $model=[];

        foreach ($this->todoItemList as $key => $entry) {
            if ($key===$id){

               $model=array(
                   'task'=> $entry->task,
                   'note'=> $entry->note,
                   'priority'=> $entry->priority
               );
            }
        }
        return $model;

    }






    public function delete(int $id){
        $id--;
        unset($this->todoItemList[$id]);
        $this->todoItemList = array_values( $this->todoItemList );
        $this->save();
    }


    // update
    public function update( int $id){

       // echo $id;

        $task=$_POST['task'];
        $note=$_POST['note'];
        $priority=$_POST['priority'];

        foreach ($this->todoItemList as $key => $entry) {
            if ($key===$id){
                $entry->task =$task;
                $entry->note =$note;
                $entry->priority =$priority;
            }
            $this->todoItemList = array_values( $this->todoItemList );
            $this->save();

        }


    }
    // status change
    public function statusChange(int $id){

        $id--;
     /*   echo '<pre>';
        print_r(   $this->todoItemList);
        echo '</pre>';
     */

        foreach ($this->todoItemList as $key => $entry) {
            if ($key===$id)
            $entry->status = 'true';
        }


      //  print_r(   $this->todoItemList[$id]);

        $this->todoItemList = array_values( $this->todoItemList );
        $this->save();
       /* $jsonString = file_get_contents( $this->db);
        $data = json_decode($jsonString, true);*/
        //   print_r($data);

    }


    private function save()
    {
        file_put_contents($this->db,json_encode($this->todoItemList));
        header("Location: /todo/index.php");
    }

}