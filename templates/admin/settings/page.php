<?php
/**
 * @var Setting[] $settings
 */

use DDaniel\Blog\Entities\Setting;
use DDaniel\Blog\Enums\SettingType;

?>

<form action="">
    <input type="hidden" name="method" value="PUT">

    <button class="btn btn-primary" type="submit">Сохранить</button>
    <br>

    <table class="table">
        <thead>
        <tr>
            <th scope="col" style="width: 5%">ID</th>
            <th scope="col" style="width: 10%">Key</th>
            <th scope="col">Value</th>
            <th scope="col" style="width: 10%"></th>
        </tr>
        </thead>
        <tbody id="settings-table">
        <?php foreach ($settings as $setting) : ?>
            <tr class="js-setting">
                <td>#<?php echo $setting->getId() ?></td>
                <td>
                    <input
                            type="text"
                            name="setting[<?php echo $setting->getId() ?>][key]"
                            value="<?php echo $setting->getKey() ?>"
                    >
                </td>
                <td>
                    <?php app()->templates->include('admin/settings/fields/' . $setting->getType()->value, [
                        'id' => $setting->getId(),
                        'value' => $setting->getValue()
                    ]) ?>
                </td>
                <td>
                    <button class="btn btn-danger js-remove-button">Удалить</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="display: flex;align-items: flex-start;">
        <select class="form-select mb-3" id="setting-type" style="max-width:300px">
            <?php foreach ( SettingType::cases() as $post_status ) : ?>
                <option value="<?php echo $post_status->value ?>" >
                    <?php echo ucfirst($post_status->value) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" id="setting-add">Добавить настройку</button>
    </div>
    <br>
    <button class="btn btn-primary" type="submit">Сохранить</button>
</form>

<template id="tmpl-setting">
    <tr class="js-setting">
        <td>New</td>
        <td>
            <input
                    type="text"
                    name="setting[%%id%%][key]"
                    value="">
        </td>
        <td>
            %%field%%
        </td>
        <td>
            <button class="btn btn-danger js-remove-button">Удалить</button>
        </td>
    </tr>
</template>

<?php foreach (SettingType::cases() as $settingType) : ?>
<template id="tmpl-setting-<?php echo $settingType->value?>">
    <?php app()->templates->include('admin/settings/fields/' . $settingType->value, [
        'id' => '%%id%%',
        'value' => ''
    ]) ?>
</template>
<?php endforeach; ?>