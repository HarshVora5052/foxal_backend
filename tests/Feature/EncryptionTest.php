<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EncryptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_phone_is_encrypted_in_database_and_decrypted_on_model(): void
    {
        $plainPhone = '9876543210';

        $user = User::factory()->create([
            'phone' => $plainPhone,
        ]);

        // 1. Verify Raw DB Column is NOT Plaintext
        $rawDbPhone = DB::table('users')->where('id', $user->id)->value('phone');

        $this->assertNotNull($rawDbPhone);
        $this->assertNotEquals($plainPhone, $rawDbPhone, 'Database column MUST NOT store plaintext phone number.');
        // AES-256 encrypted payload starts with base64 encoded JSON
        $this->assertStringContainsString('eyJ', $rawDbPhone, 'Ciphertext should be encrypted payload.');

        // 2. Verify Model Accessor Returns Decrypted Plaintext
        $retrievedUser = User::find($user->id);
        $this->assertEquals($plainPhone, $retrievedUser->phone, 'Model accessor must decrypt phone value.');
    }

    public function test_address_pii_fields_are_encrypted_in_database_and_decrypted_on_model(): void
    {
        $user = User::factory()->create();

        $plainName = 'Aditya Singhania';
        $plainPhone = '9123456780';
        $plainLine1 = 'Suite 401, Grand Horizon Towers';
        $plainLine2 = 'Marine Drive';

        $address = Address::create([
            'user_id' => $user->id,
            'full_name' => $plainName,
            'phone' => $plainPhone,
            'line1' => $plainLine1,
            'line2' => $plainLine2,
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400020',
            'is_default' => true,
        ]);

        // Verify Raw DB columns
        $rawAddress = DB::table('addresses')->where('id', $address->id)->first();

        $this->assertNotEquals($plainName, $rawAddress->full_name);
        $this->assertNotEquals($plainPhone, $rawAddress->phone);
        $this->assertNotEquals($plainLine1, $rawAddress->line1);
        $this->assertNotEquals($plainLine2, $rawAddress->line2);

        // Verify Model retrieves decrypted values
        $model = Address::find($address->id);
        $this->assertEquals($plainName, $model->full_name);
        $this->assertEquals($plainPhone, $model->phone);
        $this->assertEquals($plainLine1, $model->line1);
        $this->assertEquals($plainLine2, $model->line2);
    }

    public function test_order_address_snapshot_is_encrypted_in_database(): void
    {
        $user = User::factory()->create();
        $snapshot = [
            'full_name' => 'Meera Patel',
            'phone' => '9888877777',
            'line1' => 'Flat 12B, Ocean Heights',
            'city' => 'Surat',
            'state' => 'Gujarat',
            'pincode' => '395007',
        ];

        $order = Order::create([
            'order_number' => 'FX-TEST-001',
            'user_id' => $user->id,
            'subtotal' => 2499.00,
            'discount' => 0,
            'shipping' => 0,
            'gst_amount' => 381.20,
            'total' => 2499.00,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_PENDING,
            'payment_method' => Order::METHOD_COD,
            'address_snapshot' => $snapshot,
        ]);

        // Verify DB raw column
        $rawSnapshot = DB::table('orders')->where('id', $order->id)->value('address_snapshot');
        $this->assertNotEquals(json_encode($snapshot), $rawSnapshot);
        $this->assertStringNotContainsString('Meera Patel', $rawSnapshot);

        // Verify Model returns array
        $retrieved = Order::find($order->id);
        $this->assertIsArray($retrieved->address_snapshot);
        $this->assertEquals('Meera Patel', $retrieved->address_snapshot['full_name']);
        $this->assertEquals('9888877777', $retrieved->address_snapshot['phone']);
    }
}
