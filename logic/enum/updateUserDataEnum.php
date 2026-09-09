<?php

enum UserField: string
{
    case Username = 'username';
    case Name = 'name';
    case NoAbsen = 'no_absen';
    case Kelas = 'kelas_id';
}

enum Status: string
{
    case Accept = 'accepted';
    case Reject = 'rejected';
    case Partialy = 'partialy';
    case Pending = 'pending';
}
?>