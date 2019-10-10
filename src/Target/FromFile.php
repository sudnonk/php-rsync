<?php

    namespace sudnonk\Rsync\Target;

    /**
     * Class FromFile
     * @package sudnonk\Rsync
     * パスの末尾の/が無いと、宛先フォルダの中にこのフォルダ自体がコピーされる
     */
    class FromFile extends Target {
        /**
         * FromFile constructor.
         * @param string        $file_name
         * @param UserHost|null $userHost
         */
        public function __construct(string $file_name, UserHost $userHost = null) {
            $file = new \SplFileInfo($file_name);
            if (!$file->isReadable()) {
                throw new \RuntimeException("can not read the file.");
            }
            $this->path = $file->getRealPath();
            if ($this->path === false) {
                throw new \RuntimeException("failed to get readPath.");
            }

            $this->userHost = $userHost;
        }
    }