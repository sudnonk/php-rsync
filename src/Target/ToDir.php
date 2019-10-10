<?php


    namespace sudnonk\Rsync\Target;


    class ToDir extends Target {
        /**
         * FromFile constructor.
         * @param string        $file_name
         * @param UserHost|null $userHost
         */
        public function __construct(string $file_name, UserHost $userHost = null) {
            $file = new \SplFileInfo($file_name);
            if ($userHost === null) {
                //nullのときはローカルなのでディレクトリの存在を確認できる。
                if (!$file->isDir()) {
                    if (!mkdir($file_name)) {
                        throw new \RuntimeException("failed to mkdir.\n");
                    }
                }
                $this->path = $file->getRealPath();
                if ($this->path === false) {
                    throw new \RuntimeException("failed to get readPath.");
                }
            } else {
                $this->path = $file->getPath() . DIRECTORY_SEPARATOR . $file->getBasename();
            }

            $this->userHost = $userHost;
        }
    }