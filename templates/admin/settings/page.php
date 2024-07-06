<?php
/**
 * @var Setting[] $settings
 */

use DDaniel\Blog\Entities\Setting;

?>

<table class="table">
    <thead>
    <tr>
        <th scope="col" style="width: 5%">ID</th>
        <th scope="col" style="width: 10%">Key</th>
        <th scope="col">Value</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($settings as $setting) : ?>
        <tr>
            <td>#<?php echo $setting->getId() ?></td>
            <td>
                <input
                    type="text"
                    name="setting[<?php echo $setting->getId() ?>][key]"
                    value="<?php echo $setting->getKey() ?>"
                >
            </td>
            <td>
                <?php app()->templates->include('admin/settings/fields/' . $setting->getType()->name, [
                        'id' => $setting->getId(),
                        'value' => $setting->getValue()
                ]) ?>
            </td>
            <td>
                <button class="btn btn-danger">Удалить</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<button class="btn btn-primary">Добавить настройку</button>
