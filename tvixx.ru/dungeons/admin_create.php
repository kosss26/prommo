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
</style>

<div class="admin_container">
    <div class="admin_header">Создание нового подземелья</div>
    
    <?php if (isset($success)): ?>
    <div class="admin_message success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="admin_message error"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="create_form">
        <div class="form_title">Заполните данные для создания подземелья</div>
        
        <form action="/dungeons/admin.php?action=create" method="POST" enctype="multipart/form-data">
            <div class="form_group">
                <label class="form_label" for="name">Название подземелья</label>
                <input type="text" id="name" name="name" class="form_input" required>
            </div>
            
            <div class="form_group">
                <label class="form_label" for="description">Описание</label>
                <textarea id="description" name="description" class="form_textarea" required></textarea>
            </div>
            
            <div class="form_row">
                <div class="form_group">
                    <label class="form_label" for="min_level">Минимальный уровень</label>
                    <input type="number" id="min_level" name="min_level" class="form_input" min="1" value="1" required>
                </div>
                
                <div class="form_group">
                    <label class="form_label" for="max_level">Максимальный уровень</label>
                    <input type="number" id="max_level" name="max_level" class="form_input" min="1" value="50" required>
                </div>
            </div>
            
            <div class="form_row">
                <div class="form_group">
                    <label class="form_label" for="difficulty">Сложность</label>
                    <select id="difficulty" name="difficulty" class="form_select" required>
                        <option value="1">Легкая</option>
                        <option value="2">Нормальная</option>
                        <option value="3">Сложная</option>
                        <option value="4">Очень сложная</option>
                        <option value="5">Эпическая</option>
                    </select>
                </div>
                
                <div class="form_group">
                    <label class="form_label" for="floors">Количество этажей</label>
                    <input type="number" id="floors" name="floors" class="form_input" min="1" max="10" value="3" required>
                    <div class="form_note">От 1 до 10 этажей</div>
                </div>
            </div>
            
            <div class="form_row">
                <div class="form_group">
                    <label class="form_label" for="icon">Иконка подземелья</label>
                    <input type="file" id="icon" name="icon" class="form_file" accept="image/*">
                    <div class="form_note">Рекомендуемый размер: 128x128px</div>
                </div>
                
                <div class="form_group">
                    <label class="form_label" for="background">Фоновое изображение</label>
                    <input type="file" id="background" name="background" class="form_file" accept="image/*">
                    <div class="form_note">Рекомендуемый размер: 1200x600px</div>
                </div>
            </div>
            
            <div class="form_buttons">
                <button type="submit" name="submit" class="form_submit">Создать подземелье</button>
                <a href="/dungeons/admin.php" class="form_cancel">Отмена</a>
            </div>
        </form>
    </div>
</div> 