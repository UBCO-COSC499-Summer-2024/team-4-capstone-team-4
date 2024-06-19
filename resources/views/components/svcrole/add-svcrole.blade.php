<div>
    <section class="import-title-container">
        <div>
            <h1 class="nos">Add Service Roles</h1>
        </div>
        <div class="floater">
            <button class="import-button">
                <span class="button-title">Create New</span>
                <span class="material-symbols-outlined">add</span>
            </button>
            <button class="see-all">
                <span class="button-title">See All</span>
                <span class="material-symbols-outlined">visibility</span>
            </button>
        </div>
    </section>
    <section id="import-data" class="settings-section active glass">
        <form id="account-form" class="form">
            <div class="grouped">
                <div class="form-group">
                    <div class="grouped">
                        <div class="form-item">
                            <label class="form-label">Role: <input class="form-input" type="text" name="role"></label>
                        </div>
                        <div class="form-item">
                            <label class="form-label">Area:
                                <select class="form-select" name="area" id="area">
                                    <option value="">*Select</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-item">
                            <label class="form-label">Description:
                                <textarea class="form-input form-textarea" id="description" name="description" rows="4" cols="30"></textarea>
                            </label>
                        </div>
                        <div class="form-item">
                            <input class="form-input" type="submit" name="submit" value="Add" />
                            <input class="form-input" type="submit" name="submit" value="Cancel" />
                        </div>
                    </div>
                </div>
                <div class="form-item-grid">
                    <div class="grid-child">
                        <input type="number" name="expected_hrs_jan" />
                        <span class="title">January</span>
                    </div>
                    <div class="grid-child">
                        <!-- February -->
                        <input type="number" name="expected_hrs_feb" />
                        <span class="title">February</span>
                    </div>
                    <div class="grid-child">
                        <!-- March -->
                        <input type="number" name="expected_hrs_mar" />
                        <span class="title">March</span>
                    </div>
                    <div class="grid-child">
                        <!-- April -->
                        <input type="number" name="expected_hrs_apr" />
                        <span class="title">April</span>
                    </div>
                    <div class="grid-child">
                        <!-- May -->
                        <input type="number" name="expected_hrs_may" />
                        <span class="title">May</span>
                    </div>
                    <div class="grid-child">
                        <!-- June -->
                        <input type="number" name="expected_hrs_jun" />
                        <span class="title">June</span>
                    </div>
                    <div class="grid-child">
                        <!-- July -->
                        <input type="number" name="expected_hrs_jul" />
                        <span class="title">July</span>
                    </div>
                    <div class="grid-child">
                        <!-- August -->
                        <input type="number" name="expected_hrs_aug" />
                        <span class="title">August</span>
                    </div>
                    <div class="grid-child">
                        <!-- September -->
                        <input type="number" name="expected_hrs_sep" />
                        <span class="title">September</span>
                    </div>
                    <div class="grid-child">
                        <!-- October -->
                        <input type="number" name="expected_hrs_oct" />
                        <span class="title">October</span>
                    </div>
                    <div class="grid-child">
                        <!-- November -->
                        <input type="number" name="expected_hrs_nov" />
                        <span class="title">November</span>
                    </div>
                    <div class="grid-child">
                        <!-- December -->
                        <input type="number" name="expected_hrs_dec" />
                        <span class="title">December</span>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
