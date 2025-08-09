<style>
.create_form {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.form_title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #FFF8DC;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
}

.form_group {
    margin-bottom: 15px;
}

.form_label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #e0e0e0;
}

.form_input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    background: rgba(30, 30, 40, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    font-size: 0.95rem;
}

.form_input:focus {
    border-color: rgba(100, 149, 237, 0.5);
    outline: none;
}

.form_textarea {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    background: rgba(30, 30, 40, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    font-size: 0.95rem;
    min-height: 100px;
    resize: vertical;
}

.form_textarea:focus {
    border-color: rgba(100, 149, 237, 0.5);
    outline: none;
}

.form_select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    background: rgba(30, 30, 40, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    font-size: 0.95rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 1em;
}

.form_select:focus {
    border-color: rgba(100, 149, 237, 0.5);
    outline: none;
}

.form_row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.form_file {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    background: rgba(30, 30, 40, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e0e0e0;
    font-size: 0.95rem;
}

.form_submit {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    background: #228B22;
    margin-top: 10px;
}

.form_submit:hover {
    background: #32CD32;
    transform: scale(1.02);
}

.form_cancel {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    background: #696969;
    margin-top: 10px;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
}

.form_cancel:hover {
    background: #808080;
}

.form_note {
    font-size: 0.85rem;
    color: #A9A9A9;
    margin-top: 5px;
}

.form_buttons {
    display: flex;
    justify-content: flex-start;
    margin-top: 20px;
}

.preview_images {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.preview_image {
    background: rgba(20, 20, 30, 0.5);
    border-radius: 8px;
    padding: 10px;
}

.preview_image_title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #e0e0e0;
}

.preview_image_container {
    text-align: center;
    overflow: hidden;
    border-radius: 8px;
    background: rgba(0, 0, 0, 0.3);
}

.preview_image_container img {
    max-width: 100%;
    max-height: 150px;
}

.preview_note {
    font-size: 0.85rem;
    color: #A9A9A9;
    margin-top: 10px;
    font-style: italic;
}

.form_checkbox_container {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.form_checkbox {
    margin-right: 10px;
}

.form_checkbox_label {
    font-size: 0.95rem;
    color: #e0e0e0;
}
</style>

<div class="admin_container">
    <div class="admin_header">Редактирование подземелья</div>
    
    <?php if (isset($success)): ?>
    <div class="admin_message success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="admin_message error"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="create_form">
        <div class="form_title">Редактирование подземелья #<?= $dungeon['id'] ?></div>
        
        <?php
        // Декодируем данные JSON
        $floors_data = json_decode($dungeon['floors_data'], true);
        $rewards_data = json_decode($dungeon['rewards_data'], true);
        
        // Проверяем наличие изображений
        $has_icon = !empty($dungeon['icon']);
        $has_background = !empty($dungeon['background']);
        ?>
        
        <?php if ($has_icon || $has_background): ?>
        <div class="preview_images">
            <?php if ($has_icon): ?>
            <div class="preview_image">
                <div class="preview_image_title">Текущая иконка</div>
                <div class="preview_image_container">
                    <img src="../img/dungeons/icons/<?= $dungeon['icon'] ?>" alt="Иконка подземелья">
                </div>
                <div class="preview_note">Загрузите новую иконку, чтобы заменить текущую</div>
            </div>
            <?php endif; ?>
            
            <?php if ($has_background): ?>
            <div class="preview_image">
                <div class="preview_image_title">Текущий фон</div>
                <div class="preview_image_container">
                    <img src="../img/dungeons/backgrounds/<?= $dungeon['background'] ?>" alt="Фон подземелья">
                </div>
                <div class="preview_note">Загрузите новый фон, чтобы заменить текущий</div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <form action="/dungeons/admin.php?action=edit&id=<?= $dungeon['id'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form_group">
                <label class="form_label" for="name">Название подземелья</label>
                <input type="text" id="name" name="name" class="form_input" value="<?= $dungeon['name'] ?>" required>
            </div>
            
            <div class="form_group">
                <label class="form_label" for="description">Описание</label>
                <textarea id="description" name="description" class="form_textarea" required><?= $dungeon['description'] ?></textarea>
            </div>
            
            <div class="form_row">
                <div class="form_group">
                    <label class="form_label" for="min_level">Минимальный уровень</label>
                    <input type="number" id="min_level" name="min_level" class="form_input" min="1" value="<?= $dungeon['min_level'] ?>" required>
                </div>
                
                <div class="form_group">
                    <label class="form_label" for="max_level">Максимальный уровень</label>
                    <input type="number" id="max_level" name="max_level" class="form_input" min="1" value="<?= $dungeon['max_level'] ?>" required>
                </div>
            </div>
            
            <div class="form_group">
                <label class="form_label" for="difficulty">Сложность</label>
                <select id="difficulty" name="difficulty" class="form_select" required>
                    <option value="1" <?= $dungeon['difficulty'] == 1 ? 'selected' : '' ?>>Легкая</option>
                    <option value="2" <?= $dungeon['difficulty'] == 2 ? 'selected' : '' ?>>Нормальная</option>
                    <option value="3" <?= $dungeon['difficulty'] == 3 ? 'selected' : '' ?>>Сложная</option>
                    <option value="4" <?= $dungeon['difficulty'] == 4 ? 'selected' : '' ?>>Очень сложная</option>
                    <option value="5" <?= $dungeon['difficulty'] == 5 ? 'selected' : '' ?>>Эпическая</option>
                </select>
            </div>
            
            <div class="form_row">
                <div class="form_group">
                    <label class="form_label" for="icon">Новая иконка подземелья</label>
                    <input type="file" id="icon" name="icon" class="form_file" accept="image/*">
                    <div class="form_note">Рекомендуемый размер: 128x128px</div>
                </div>
                
                <div class="form_group">
                    <label class="form_label" for="background">Новый фон подземелья</label>
                    <input type="file" id="background" name="background" class="form_file" accept="image/*">
                    <div class="form_note">Рекомендуемый размер: 1200x600px</div>
                </div>
            </div>
            
            <div class="form_group">
                <div class="form_checkbox_container">
                    <input type="checkbox" id="active" name="active" class="form_checkbox" <?= $dungeon['active'] ? 'checked' : '' ?>>
                    <label for="active" class="form_checkbox_label">Подземелье активно</label>
                </div>
                <div class="form_note">Если отключено, игроки не смогут войти в это подземелье</div>
            </div>
            
            <div class="form_buttons">
                <button type="submit" name="submit" class="form_submit">Сохранить изменения</button>
                <a href="/dungeons/admin.php" class="form_cancel">Отмена</a>
            </div>
        </form>
    </div>
</div> 