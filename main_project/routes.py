from flask import render_template, url_for, flash, redirect, request
from datetime import datetime
from main_project import app, db, bcrypt
from main_project.forms import (RegistrationForm, LoginForm, BankAccountForm)
from main_project.models import User, BankAccount
from flask_login import login_user, current_user, logout_user, login_required

@app.route("/")
@app.route("/dashboard")
@login_required
def home():
    #if current_user.email_address != 'loumonth.jack.jr@gmail.com':
    #    return redirect(url_for('addbank'))
    #elif current_user.email_address == 'loumonth.jack.jr@gmail.com':
    page = request.args.get('page', 1, type=int)
    users = User.query.order_by(User.date_posted.desc()).paginate(page=page, per_page=10)
    return render_template('dashboard.html', users=users)

@app.route("/login", methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('home'))
    form = LoginForm()
    if form.validate_on_submit():
        user = User.query.filter_by(email_address=form.email_address.data).first()
        if user and bcrypt.check_password_hash(user.password, form.password.data):
            login_user(user, remember=form.remember.data)
            next_page = request.args.get('next')
            return redirect(next_page) if next_page else redirect(url_for('home'))
        else:
            flash('Login Unsuccessful, Please Check Your Credentials are Entered Correctly', 'danger')
    return render_template('login.html', title='Login', form=form)

@app.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('home'))

@app.route("/register", methods=['GET', 'POST'])
def register():
    if current_user.is_authenticated:
        return redirect(url_for('login'))
    form = RegistrationForm()
    if form.validate_on_submit():
        hashed_password = bcrypt.generate_password_hash(form.password.data).decode('utf-8')
        user = User(email_address=form.email_address.data, password=hashed_password)
        db.session.add(user)
        db.session.commit()
        flash('Your account has been created! You are now able to log in', 'success')
        return redirect(url_for('login'))
    return render_template('register.html', title='Register', form=form)

@app.route("/addbank", methods=['GET', 'POST'])
@login_required
def addbank():
    form = BankAccountForm()
    if request.method == 'POST':
        newBankAccount = BankAccount(
            account_number=request.form['account_number'],
            routing_number=request.form['routing_number'],
            account_owner=current_user.id)
        db.session.add(newBankAccount)
        db.session.commit()
        flash('New Bank Account has been Updated!')
        return redirect(url_for('home'))
    return render_template('add_bank_info.html', title="New Bank Account", form=form, legend='Add Bank Account')

@app.route('/bank/<int:user_id>', methods=['GET'])
def display_bank_info(user_id):
    exist = db.session.query(db.exists().where(BankAccount.id == 1)).scalar() is None
    if exist:
        flash('Bank Account Information hasn\'t been created yet, Please Add Bank Account Information')
        return redirect(url_for('home'))
    user = User.query.filter_by(id=User.id).first()
    bankAccount = BankAccount.query.get_or_404(user_id)
    return render_template('bank_account_info.html', title='Bank Account Information', bankAccount=bankAccount, user=user)
