Vim�UnDo� +����t����>��/�:��O�E^Fo�����0   #         //   !                          Z���    _�                             ����                                                                                                                                                                                                                                                                                                                                                             Z��     �                  <?php       &use Illuminate\Support\Facades\Schema;   )use Illuminate\Database\Schema\Blueprint;   -use Illuminate\Database\Migrations\Migration;       class Spots extends Migration   {       /**        * Run the migrations.        *        * @return void        */       public function up()       {   
        //       }           /**        * Reverse the migrations.        *        * @return void        */       public function down()       {   
        //       }   }5�_�                            ����                                                                                                                                                                                                                                                                                                                                                             Z��
     �                     //�             5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��     �                       Schema::create()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��     �                       Schema::create("")5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��     �                       Schema::create("spots")5�_�                       +    ����                                                                                                                                                                                                                                                                                                                                                             Z��     �               +        Schema::create("spots", function())5�_�                       )    ����                                                                                                                                                                                                                                                                                                                                                             Z��#     �               ,        Schema::create("spots", function());5�_�      	                 :    ����                                                                                                                                                                                                                                                                                                                                                             Z��9     �               <        Schema::create("spots", function(Blueprint $table));5�_�      
           	      ;    ����                                                                                                                                                                                                                                                                                                                                                             Z��:     �               >        Schema::create("spots", function(Blueprint $table){});5�_�   	              
          ����                                                                                                                                                                                                                                                                                                                                                             Z��=     �               
          �             5�_�   
                        ����                                                                                                                                                                                                                                                                                                                                                             Z��G     �                         $table->increments()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��H     �                          $table->increments("")5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                             Z��I     �               "          $table->increments("id")5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��e     �                          $table->bigInteger()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z��f     �                           $table->bigInteger("")5�_�                       (    ����                                                                                                                                                                                                                                                                                                                                                             Z��m     �                (          $table->bigInteger("store_id")5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         !                $table->dateTime()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         !                $table->dateTime("")5�_�                       '    ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         !      '          $table->dateTime("slot_time")5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         "                $table->spots()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         "                $table->integer()5�_�                           ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         "                $table->integer("")5�_�                       "    ����                                                                                                                                                                                                                                                                                                                                                             Z���     �         "      "          $table->integer("slots")5�_�                        	    ����                                                                                                                                                                                                                                                                                                                                                             Z���     �       "   #            //�       "   "    5�_�                    !       ����                                                                                                                                                                                                                                                                                                                                                             Z���     �       "   #              Schema::drop()5�_�                    !       ����                                                                                                                                                                                                                                                                                                                                                             Z���     �       "   #              Schema::drop("")5�_�                     !       ����                                                                                                                                                                                                                                                                                                                                                             Z���    �       "   #              Schema::drop("spots")5��