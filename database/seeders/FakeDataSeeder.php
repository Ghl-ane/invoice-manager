<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class FakeDataSeeder extends Seeder
{
    // Demo credentials — shown in README for portfolio reviewers
    private const EMAIL    = 'demo@invoicex.app';
    private const PASSWORD = 'password';
    private const NAME     = 'Alex Morgan';

    public function run(): void
    {
        $user = \App\Models\User::firstOrCreate(
            ['email' => self::EMAIL],
            ['name' => self::NAME, 'password' => Hash::make(self::PASSWORD)],
        );

        // Skip only if both clients AND invoices are present (complete seed)
        $hasClients  = DB::table('clients')->where('user_id', $user->id)->exists();
        $hasInvoices = DB::table('invoices')->where('user_id', $user->id)->exists();

        if ($hasClients && $hasInvoices) {
            $this->command->info('Demo data already seeded — skipping.');
            return;
        }

        // Partial seed from a previous failed run — clean up and redo
        if ($hasClients) {
            DB::table('clients')->where('user_id', $user->id)->delete();
        }

        $clients = $this->insertClients($user->id);
        $this->insertInvoices($user->id, $clients);

        $this->command->info('Demo account ready → ' . self::EMAIL . ' / ' . self::PASSWORD);
    }

    private function insertClients(int $userId): array
    {
        $rows = [
            ['name' => 'Acme Corporation',    'email' => 'billing@acme.com',      'phone' => '+1 212 555 0100', 'address' => '123 Fifth Ave, New York, USA'],
            ['name' => 'Stark Industries',    'email' => 'ap@stark.io',           'phone' => '+1 310 555 0198', 'address' => '10880 Malibu Point, CA, USA'],
            ['name' => 'Global Tech Ltd',     'email' => 'accounts@globaltech.io','phone' => '+44 20 7946 0958','address' => '10 Downing St, London, UK'],
            ['name' => 'Horizon Agency',      'email' => 'pay@horizon.agency',    'phone' => '+33 1 42 00 00 01','address' => '15 Rue de la Paix, Paris, France'],
            ['name' => 'Nexus Solutions',     'email' => 'finance@nexus.sa',      'phone' => '+966 11 555 0101','address' => 'King Fahd Road, Riyadh, KSA'],
            ['name' => 'Atlas Digital',       'email' => 'hello@atlas.ma',        'phone' => '+212 5 22 00 0001','address' => 'Boulevard Zerktouni, Casablanca, Morocco'],
            ['name' => 'Vertex Studios',      'email' => 'billing@vertex.ca',     'phone' => '+1 416 555 0132', 'address' => '200 Bay St, Toronto, Canada'],
            ['name' => 'Meridian Consulting', 'email' => 'inv@meridian.com.au',   'phone' => '+61 2 9000 0001', 'address' => '1 Martin Place, Sydney, Australia'],
        ];

        foreach ($rows as &$row) {
            $row['user_id']    = $userId;
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('clients')->insert($rows);

        return DB::table('clients')->where('user_id', $userId)->pluck('id')->toArray();
    }

    private function insertInvoices(int $userId, array $clientIds): void
    {
        // Spread paid invoices across the last 6 months so the revenue chart has data
        $invoices = [
            // 5 months ago
            ['client' => 0, 'offset' => 5, 'status' => 'paid',    'currency' => 'USD', 'items' => [['Web Development',       10, 150.00]]],
            ['client' => 1, 'offset' => 5, 'status' => 'paid',    'currency' => 'EUR', 'items' => [['UI/UX Design',            5, 200.00]]],
            // 4 months ago
            ['client' => 2, 'offset' => 4, 'status' => 'paid',    'currency' => 'GBP', 'items' => [['Backend API Development', 8, 120.00], ['Code Review', 3, 80.00]]],
            ['client' => 3, 'offset' => 4, 'status' => 'paid',    'currency' => 'EUR', 'items' => [['Mobile App Development', 20, 175.00]]],
            // 3 months ago
            ['client' => 4, 'offset' => 3, 'status' => 'paid',    'currency' => 'SAR', 'items' => [['SEO Optimization',        3, 300.00]]],
            ['client' => 5, 'offset' => 3, 'status' => 'paid',    'currency' => 'MAD', 'items' => [['Branding Package',         1, 800.00]]],
            ['client' => 6, 'offset' => 3, 'status' => 'overdue', 'currency' => 'CAD', 'items' => [['Cloud Migration',         15, 130.00]]],
            // 2 months ago
            ['client' => 7, 'offset' => 2, 'status' => 'paid',    'currency' => 'AUD', 'items' => [['Database Optimisation',   6, 110.00]]],
            ['client' => 0, 'offset' => 2, 'status' => 'paid',    'currency' => 'USD', 'items' => [['E-commerce Integration',  12, 160.00]]],
            // 1 month ago
            ['client' => 1, 'offset' => 1, 'status' => 'paid',    'currency' => 'USD', 'items' => [['Security Audit',          2, 500.00], ['Penetration Test', 1, 750.00]]],
            ['client' => 2, 'offset' => 1, 'status' => 'sent',    'currency' => 'GBP', 'items' => [['DevOps Setup',             7, 140.00]]],
            // This month
            ['client' => 3, 'offset' => 0, 'status' => 'sent',    'currency' => 'EUR', 'items' => [['Performance Audit',       4, 250.00]]],
            ['client' => 4, 'offset' => 0, 'status' => 'draft',   'currency' => 'SAR', 'items' => [['Maintenance Retainer',    1, 1200.00]]],
            ['client' => 5, 'offset' => 0, 'status' => 'draft',   'currency' => 'USD', 'items' => [['Landing Page Design',     1, 950.00], ['Copywriting', 1, 300.00]]],
        ];

        foreach ($invoices as $index => $inv) {
            $issueDate = Carbon::now()->subMonths($inv['offset'])->startOfMonth()->addDays(rand(0, 10));
            $dueDate   = $issueDate->copy()->addDays(30);
            $number    = 'INV-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $total = collect($inv['items'])->sum(fn ($i) => $i[1] * $i[2]);

            $invoiceId = DB::table('invoices')->insertGetId([
                'user_id'        => $userId,
                'client_id'      => $clientIds[$inv['client']],
                'invoice_number' => $number,
                'issue_date'     => $issueDate->toDateString(),
                'due_date'       => $dueDate->toDateString(),
                'status'         => $inv['status'],
                'currency'       => $inv['currency'],
                'total'          => $total,
                'notes'          => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            foreach ($inv['items'] as [$desc, $qty, $price]) {
                DB::table('invoice_items')->insert([
                    'invoice_id'  => $invoiceId,
                    'description' => $desc,
                    'quantity'    => $qty,
                    'unit_price'  => $price,
                    'subtotal'    => $qty * $price,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
