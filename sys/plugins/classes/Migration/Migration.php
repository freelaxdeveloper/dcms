<?php
namespace App\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;
use App\Migration\Connect; // Trait

class Migration extends AbstractMigration {
  use Connect;

}