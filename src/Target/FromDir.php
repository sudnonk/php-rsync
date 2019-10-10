<?php


    namespace sudnonk\Rsync\Target;

    /**
     * Class FromDir
     * @package sudnonk\Rsync
     * パスの末尾の/があると、宛先フォルダの中にこのフォルダの中身だけがコピーされる
     */
    class FromDir extends Target {

        /**
         * FromFile constructor.
         * @param string        $dir_name
         * @param UserHost|null $userHost
         */
        public function __construct(string $dir_name, UserHost $userHost = null) {
            $dir = new \SplFileInfo($dir_name);
            if($userHost === null) {//nullのときはローカルなのでisDirが使える
                if (!$dir->isDir()) {
                    throw new \RuntimeException("the file is not dir.");
                }
                $this->path = $dir->getRealPath();
                if ($this->path === false) {
                    throw new \RuntimeException("failed to get readPath.");
                }
            }else{
                $this->path = $dir->getPath() . DIRECTORY_SEPARATOR . $dir->getBasename();
            }
            $this->path .= DIRECTORY_SEPARATOR;

            $this->userHost = $userHost;
        }
    }