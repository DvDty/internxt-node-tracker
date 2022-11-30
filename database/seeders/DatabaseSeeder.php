<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Country;
use App\Models\LedgerType;
use App\Models\Node;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function __construct(protected Faker $faker)
    {
        $this->faker = resolve(Faker::class);
    }

    public function run(): void
    {
        $this->seedCountries();
        Address::factory(150)->create();

        DB::table('protocols')->insert([
            ['name' => '1.2.0-INXT'],
            ['name' => '1.2.0'],
        ]);

        $ledgerTypeIdStatus = LedgerType::firstWhere('name', 'status')->id;
        $ledgerTypeIdReputation = LedgerType::firstWhere('name', 'reputation')->id;

        Node::factory(150)
            ->create()
            ->each(function (Node $node) use ($ledgerTypeIdStatus, $ledgerTypeIdReputation) {
                $ledgerRecordsToInsert = [];

                $created_at = Carbon::now();

                foreach (range(1, $insertedStatusesCount = 7 * 24) as $i) {
                    $ledgerRecordsToInsert[] = [
                        'ledger_type_id' => $ledgerTypeIdStatus,
                        'node_id' => $node->id,
                        'value' => $this->faker->randomElement([0, 1, 1, 1]),
                        'created_at' => $created_at->toDateTimeString(),
                    ];

                    $created_at->subHour();
                }

                $created_at = Carbon::now();

                foreach (range(1, mt_rand(5, 7)) as $i) {
                    $ledgerRecordsToInsert[] = [
                        'ledger_type_id' => $ledgerTypeIdReputation,
                        'node_id' => $node->id,
                        'value' => $a = $this->faker->numberBetween(0, 5000),
                        'created_at' => $created_at->toDateTimeString(),
                    ];

                    $created_at->subDay();
                }

                $node->update([
                    'status' => Arr::get($ledgerRecordsToInsert, 0)['value'],
                    'reputation' => Arr::get($ledgerRecordsToInsert, $insertedStatusesCount)['value'],
                ]);

                DB::table('ledger_records')->insert($ledgerRecordsToInsert);
            });

        Address::all()->each(function (Address $address) {
            $address->recalculateReputation();
        });
    }

    protected function seedCountries(): void
    {
        $countries = [
            ['name' => 'United States', 'code' => 'us'],
            ['name' => 'Germany', 'code' => 'de'],
            ['name' => 'Japan', 'code' => 'jp'],
            ['name' => 'France', 'code' => 'fr'],
            ['name' => 'Bulgaria', 'code' => 'bg'],
            ['name' => 'China', 'code' => 'cn'],
            ['name' => 'Indonesia', 'code' => 'id'],
            ['name' => 'South Korea', 'code' => 'kr'],
            ['name' => 'United Kingdom', 'code' => 'gb'],
            ['name' => 'Mexico', 'code' => 'mx'],
            ['name' => 'Brazil', 'code' => 'br'],
            ['name' => 'Argentina', 'code' => 'ar'],
            ['name' => 'South Africa', 'code' => 'za'],
            ['name' => 'Canada', 'code' => 'ca'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate([
                'name' => $country['name'],
                'code' => $country['code'],
            ]);
        }
    }
}
