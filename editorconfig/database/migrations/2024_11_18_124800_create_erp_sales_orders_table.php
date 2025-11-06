<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('erp_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string("so_id");
            $table->string("so_qr_code")->nullable();
            $table->string("so_customername");
            $table->string("so_customerid");
            $table->string("so_no");
            $table->string("so_quotationno");
            $table->string("so_quotationid");
            $table->string("so_quotationdate");
            $table->string("so_enquiryno");
            $table->string("so_enquiryid");
            $table->string("so_cpono");
            $table->string("so_cpodate");
            $table->string("so_cpoid");
            $table->string("so_revision");
            $table->string("so_editcount");
            $table->string("so_cporecdate");
            $table->string("so_department");
            $table->string("so_group");
            $table->string("so_groupid");
            $table->string("so_unitname");
            $table->string("so_unitid");
            $table->string("so_region");
            $table->string("so_regionid");
            $table->text("so_contactperson");
            $table->string("so_type");
            $table->string("so_cpotype");
            $table->string("so_factoring");
            $table->string("so_currency");
            $table->string("so_exchangerate");
            $table->string("so_ordertype");
            $table->text("so_agent");
            $table->string("so_quotedate");
            $table->string("so_billname");
            $table->text("so_deliverytimeline");
            $table->text("so_cpodeliverytimeline");
            $table->text("so_technicalnotes");
            $table->text("so_instruction");
            $table->longText("so_itemjson");
            $table->text("so_commenthistory");
            $table->text("so_commercialterm");
            $table->text("so_attachments")->nullable();
            $table->text("so_sodeliverystages");
            $table->string("so_deliverybasis");
            $table->string("so_description");
            $table->string("so_status");
            $table->string("so_rejectreason")->nullable();
            $table->string("so_date");
            $table->string("so_customercode");
            $table->string("so_totalqty");
            $table->string("so_totalpcs");
            $table->string("so_holdreason");
            $table->string("so_approvalcount");
            $table->string("so_additionaloperationnotes");
            $table->string("so_additionaloperationnotes2");
            $table->string("scr_status");
            $table->string("so_freetrial");
            $table->string("ldclause");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_sales_orders');
    }
};
