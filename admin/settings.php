<?php

function progress_global_settings()
{
    title();
    ranges();
}

function title()
{
?>
    <section class="<?= AMH_NJ_DBC_PREFIX ?>-settings">
        <h3>
            تنظیمات تخفیف محصول بر اساس تعداد خرید کاربر
        </h3>
        <hr />
        <p>
            تعداد و قیمت مورد نظر برای تعداد را مشخص کنید.
        </p>
        <p>
            شما می توانید چند بازه را خالی بگزارید.
        </p>
        <p>
            ترتیب را رعایت کنید.
        </p>
    <?php
}

function ranges()
{
    global $wpdb;

    $optionName = AMH_NJ_DBC_PREFIX . AMH_NJ_DBC_PLUGIN_NAME;
    $tableName = $wpdb->prefix . 'options';

    $resultQuery = $wpdb->get_results("SELECT * FROM $tableName WHERE option_name='$optionName'");

    if (exists($resultQuery)) {
        $resultQuery = $resultQuery[0];
        $result = json_decode($resultQuery->option_value, true);
    }

    $submitBtnName  = AMH_NJ_DBC_PREFIX . "save";
    ?>
        <form method="POST">
            <label>شناسه محصول مورد نظر</label>
            <input type="text" id="product_id" value="<?php if (exists($result)) echo $result["product_id"] ?>" name="product_id" />

            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <hr />
                <label>تعداد فروش</label>
                <input type="text" id="range_<?= $i ?>" value="<?php if (exists($result)) echo $result["range_$i"] ?>" name="range_<?= $i ?>" />

                <label>قیمت فروش</label>
                <input type="text" class="price" id="price_<?= $i ?>" value="<?php if (exists($result)) echo $result["price_$i"] ?>" name="price_<?= $i ?>" />
            <?php endfor; ?>

            <hr />
            <button type="submit" name="<?= $submitBtnName ?>">ثبت</button>
        </form>
    </section>
<?php

    if (!isset($_POST[$submitBtnName])) return;

    $optionValue = [];
    foreach ($_POST as $key => $value) {
        $optionValue[$key] = $value;
    }

    $dataToInsertOrUpdate = [
        "option_name" => $optionName,
        "option_value" => json_encode($optionValue),
    ];

    if (!exists($resultQuery)) {
        //insert
        try {
            $wpdb->insert($tableName, $dataToInsertOrUpdate);
        } catch (Exception $e) {
            echo "<div class='notice notice-error'>
                    <p>
                    عملیات انجام نشد
                    </p>
                </div>";
            return;
        }
    } else {
        try {
            $wpdb->update(
                $tableName,
                $dataToInsertOrUpdate,
                array(
                    "option_id" => $resultQuery->option_id
                )
            );
        } catch (\Exception $e) {
            echo "<div class='notice notice-error'>
                    <p>
                    عملیات انجام نشد
                    </p>
                </div>";
            return;
        }
    }

    echo "<div class='notice notice-success'>
            <p>
            عملیات با موفقیت انجام شد
            </p>
        </div>";

    foreach ($optionValue as $key => $value) {
        echo "<script type='text/javascript'>
            document.getElementById('$key').value = '$value';
        </script>";
    }
}
