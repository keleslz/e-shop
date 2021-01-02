<?php
namespace App\Lib\Session;

class Session 
{   
    //TODO Ajouter expiration de session
    /** Session which will be no display to alert user */
    const NO_DISPLAY = ['_userStart','_cart','_customer'];

    /**
     * Run a session_start
     */
    public function __construct()
    {   
        $this->run();
    }

    public function run()
    {
        if(session_status() !== 2)
        {
            session_start();
        }
    }

    /**
     * Create a new value in $_SESSION array and can be diplayed to the user
     * @param string $section ex : 'user'
     * @param string $type ex : 'success'
     * @param string $value ex : 'Connection reussie !'
     */
    public function set(string $section, string $type , $value) : void
    {   
        $_SESSION[$section][$type][] = $value;
    }

     /**
     * Get $key in current session
     */
    public function get(string $key)
    {   
        return $_SESSION[$key] ?? [];
    }

     /**
     * Destroy a session section or type of session section
     */
    public function destroy(string $section , string $type = '') : void
    {   
        empty($type) 
        ? $_SESSION[$section] = null 
        : $_SESSION[$section][$type] = null;
    }
    
    /**
     * Display all current errors except '_userStart', '_cart' array
     */
    public function display()
    {
        //TODO Creer une methode qui gerera chaque element du tableau NO_DISPLAY proprement
        foreach ($_SESSION as $section => $types) {

            if( is_array($_SESSION[$section]) && 
            !($section === self::NO_DISPLAY[0]) && !($section === self::NO_DISPLAY[1]) && !($section === self::NO_DISPLAY[2]) && $types !== null)
            {
                foreach ($types as $key => $value) {

                    if( $_SESSION[$section][$key] !== null)
                    {   
                        $this->messageType($key,$value);
                        $_SESSION[$section][$key] = null;
                    }
                }
            }else{
                $key  = $section;
                $value = $types; 
                $this->messageType($key, $types);
            }

        }
    }

    /**
     * echo message type 'error', 'success', 'info' .. etc
     */
    private function messageType(string $key, $value)
    {   
        if($key === 'error')
        {   
            foreach ($value as $key => $val) {
                echo "
                    <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative ' role='alert'>
                        <span class='block sm:inline'>{$val}</span>
                    </div>
                ";
            }

            return ;
        }

        if($key === 'success')
        {
            foreach ($value as $key => $val) {
                echo "
                    <div class='bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md ' role='alert'>
                        <div class='flex'>
                            <div class='py-1'><svg class='fill-current h-6 w-6 text-teal-500 mr-4' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><path d='M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z'/></svg></div>
                            <div class='flex items-center'>
                                <p class='text-sm'>{$val}</p>
                            </div>
                        </div>
                    </div>
                ";
            }
            return ;
        }
        
        if($key === 'info')
        {
            foreach ($value as $key => $val) {
                echo "
                    <div class='flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 ' role='alert'>
                        <svg class='fill-current w-4 h-4 mr-2' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'><path d='M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z'/></svg>
                        <p>{$val}.</p>
                    </div>";
            }
        }
    }


}