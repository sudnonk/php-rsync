<?php


    namespace sudnonk\Rsync;

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
            if (!$dir->isDir()) {
                throw new \RuntimeException("the file is not dir.");
            }
            $this->path = $dir->getRealPath();
            if ($this->path === false) {
                throw new \RuntimeException("failed to get readPath.");
            }
            $this->path .= DIRECTORY_SEPARATOR;

            $this->userHost = $userHost;
        }
    }