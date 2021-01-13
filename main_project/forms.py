from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, SubmitField, BooleanField, IntegerField, SelectField
from wtforms.validators import DataRequired, Length, Email, EqualTo, ValidationError
from main_project.models import User, BankAccount

"""This is the form used to get input from users, this allows them to register which lets them
be able to login to gain access to login required routes, this form is controlled by register route function
which adds the form data into database if it does not already exist"""

class RegistrationForm(FlaskForm):
    email_address = StringField('Email Address', validators=[DataRequired(), Email()])
    password = PasswordField('Password', validators=[DataRequired()])
    confirm_password = PasswordField('Confirm Password',
                                     validators=[DataRequired(), EqualTo('password')])
    submit = SubmitField('Onboard')

    @staticmethod
    def validate_email(self, email_address):
        user = User.query.filter_by(email_address=email_address.data).first()
        if user:
            raise ValidationError('That email address is taken. Please choose a different one.')


"""This is the form used to get input from users, this allows them to login which lets them gain access login required
routes. This form data is validated that exist in database by login route function"""


class LoginForm(FlaskForm):
    email_address = StringField('Email Address', validators=[DataRequired(), Email()])
    password = PasswordField('Password', validators=[DataRequired()])
    remember = BooleanField('Remember Me')
    submit = SubmitField('Login')

class BankAccountForm(FlaskForm):
    account_number = IntegerField('Account Number', validators=[DataRequired()])
    confirm_account_number = IntegerField('Confirm Account Number', validators=[DataRequired(), EqualTo('account_number')])
    routing_number = IntegerField('Routing Number', validators=[DataRequired()])
    submit = SubmitField('Add Bank Account')
    
    @staticmethod
    def validate_account(self, account_number):
        bank_account_number = BankAccount.query.filter_by(account_number=account_number.data).first()
        if bank_account_number is not None:
            raise ValidationError('Bank Account Number Already Exists, Cannot be Reused')
    
    @staticmethod
    def validate_routing(self, routing_number):
        bank_routing_number = BankAccount.query.filter_by(routing_number=routing_number.data).first()
        if bank_routing_number is not None:
            raise ValidationError('Bank Routing Does not Exist')

class SearchForm(FlaskForm):
    account_number = IntegerField('Account Number', validators=[DataRequired()])
    confirm_account_number = IntegerField('Confirm Account Number', validators=[DataRequired(), EqualTo('account_number')])
    routing_number = IntegerField('Routing Number', validators=[DataRequired()])
    submit = SubmitField('Search Account')

    @staticmethod
    def validate_account(self, account_number):
        bank_account_number = BankAccount.query.filter_by(account_number=account_number.data).first()
        if bank_account_number is not None:
            raise ValidationError('Bank Account Does not Exist')

    @staticmethod
    def validate_routing(self, routing_number):
        bank_routing_number = BankAccount.query.filter_by(routing_number=routing_number.data).first()
        if bank_routing_number is not None:
            raise ValidationError('Bank Routing Does not Exist')