<?php

class Good_Job
{
    public function perform()
    {
        sleep(1);

        return true;
    }
}

class Bad_Job
{
    public function perform()
    {
        sleep(1);
        throw new Exception('Something went wrong');
    }
}