<?php

require_once ROOT . DS . 'src/Lib/fpdf/fpdf.php'; // Get Tool


//Directory src/

require_once ROOT . DS . 'core/functions-dev.php';
require_once ROOT . DS . 'core/functions-prod.php';

//Entity
require_once ROOT . DS . 'src/Entity/User.php'; // Get User entity 
require_once ROOT . DS . 'src/Entity/Product.php'; // Get Product entity  
require_once ROOT . DS . 'src/Entity/File.php'; // Get File entity 
require_once ROOT . DS . 'src/Entity/Image.php'; // Get Image entity 
require_once ROOT . DS . 'src/Entity/Category.php'; // Get Category entity 
require_once ROOT . DS . 'src/Entity/Client.php'; // Get Client entity 
require_once ROOT . DS . 'src/Entity/Shop.php'; // Get Client entity 
require_once ROOT . DS . 'src/Entity/Pdf.php'; // Get Pdf entity 
require_once ROOT . DS . 'src/Entity/Bill.php'; // Get Bill entity 

//Session
require_once ROOT . DS . 'src/Lib/Session/Session.php'; // Start Session
require_once ROOT . DS . 'src/Lib/Session/UserSession.php'; // GEt UserSession

//AbstractClass
require_once ROOT . DS . 'src/AbstractClass/AbstractController.php'; // Get AbstractController

//Lib
require_once ROOT . DS . 'src/Lib/Tool.php'; // Get Tool

//Lib/File
//++
require_once ROOT . DS . 'src/Lib/File/FileError.php'; // Get FileError
require_once ROOT . DS . 'src/Lib/File/FileValidator.php'; // Get FileValidator

//Lib/image
//++

//Lib/input
//++
require_once ROOT . DS . 'src/Lib/input/InputValidator.php'; // Get inputValidator
require_once ROOT . DS . 'src/Lib/input/InputError.php'; // Get inputValidator
require_once ROOT . DS . 'src/Lib/input/Input.php'; // Get inputConform

//Repo
require_once ROOT . DS . 'src/Repository/Repository.php'; // Get Repository
require_once ROOT . DS . 'src/Repository/UserRepository.php'; // Get UserRepository
require_once ROOT . DS . 'src/Repository/ProductRepository.php'; // Get ProductRepository
require_once ROOT . DS . 'src/Repository/FileRepository.php'; // Get FileRepository
require_once ROOT . DS . 'src/Repository/ImageRepository.php'; // Get ImageRepository
require_once ROOT . DS . 'src/Repository/CategoryRepository.php'; // Get ImageRepository