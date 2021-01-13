from flask import render_template, url_for, flash, redirect, request
from datetime import datetime
from sqlalchemy import func
import os
from sqlalchemy.exc import IntegrityError
from main_project import app, db, bcrypt, company
from main_project.forms import (RegistrationForm, LoginForm, BankAccountForm, SearchForm)
from main_project.models import User
from flask_login import login_user, current_user, logout_user, login_required

@app.route("/")
@app.route("/dashboard")
@login_required
def home():
    if current_user.email_address != 'Landlord@' + company + '.com':
        page = request.args.get('page', 1, type=int)
        user = User.query.filter_by(id=User.id).paginate(page=page, per_page=10)
        return render_template('Tenant-Dashboard.html', user=user, title='Tenant Dashboard', legend=company)
    elif current_user.email_address == 'Landlord@' + company + '.com':
        page = request.args.get('page', 1, type=int)
        users = User.query.order_by(User.date_posted.desc()).paginate(page=page, per_page=10)
    return render_template('Landlord-Dashboard.html', users=users, title='Landlord Dashboard', legend=company)

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
            flash('Login Unsuccessful, Please Check Your Credentials', 'danger')
    return render_template('Login.html', title='Login', form=form, legend=company)

@app.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('home'))



@app.route('/card/')
@login_required
def card_payment():
    return render_template('Card_Payment.html', title='Card Payment', legend=company)

@app.route('/bankAccount/')
@login_required
def bank_payment():
    return render_template('Bank_Account_Payment.html', title='Bank Account Payment', legend=company)

@app.route('/sendMessage/')
@login_required
def send_message():
    return render_template('Inquiry_Concern_Form.html', title='Message Landlord', legend=company)

@app.route('/setupPayment/')
@login_required
def setup_payment():
    return render_template('Recurring_Payment.html', title='Set Up Payment', legend=company)

@app.route('/documents/')
@login_required
def documents():
    return render_template('Documents.html', title='Documents', legend=company)

@app.route("/register", methods=['GET', 'POST'])
def register():
    try:
        if current_user.is_authenticated:
            form = RegistrationForm()
            if form.validate_on_submit():
                hashed_password = bcrypt.generate_password_hash(form.password.data).decode('utf-8')
                user = User(email_address=form.email_address.data, password=hashed_password)
                db.session.add(user)
                db.session.commit()
                os.system("php /home/loumonth/Flask-App/AmazonServices/Run.php 'New Account Registered'")
                flash('New Tenant Account has been created!', 'success')
                return redirect(url_for('register'))
        else:
            return redirect(url_for('login'))
        return render_template('Register.html', title='Onboard New Tenant', form=form, legend=company)

    except IntegrityError:
        flash('User already Taken')
        return redirect(url_for('home'))

@app.errorhandler(404)
def page_not_found(e):
    # note that we set the 404 status explicitly
    return render_template('404.html', legend=company), 404