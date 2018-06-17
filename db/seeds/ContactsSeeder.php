<?php

use Phinx\Seed\AbstractSeed;

class ContactsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $ddd_list = ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '22', '24', '27', '28', '31', '32', '33', '34', '35', '37', '38', '41', '42', '43', '44', '45', '46', '47', '48', '49', '51', '53', '54', '55', '61', '62', '63', '64', '65', '66', '67', '68', '69', '71', '73', '74', '75', '77', '79', '81', '82', '83', '84', '85', '86', '87', '88', '89', '91', '92', '93', '94', '95', '96', '97', '98', '99'];

        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'main_number' => '('.$faker->randomElement($ddd_list).') ' . $faker->randomNumber(rand(4,5)) . '-' . $faker->randomNumber(4),
                'secondary_number' => '('.$faker->randomElement($ddd_list).') ' . $faker->randomNumber(rand(4,5)) . '-' . $faker->randomNumber(4),
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ];
        }

        $this->table('contacts')->insert($data)->save();
    }
}
