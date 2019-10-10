<?php


    namespace sudnonk\Rsync;


    class ToDir extends Target {
        /**
         * FromFile constructor.
         * @param string        $file_name
         * @param UserHost|null $userHost
         */
        public function __construct(string $file_name, UserHost $userHost = null) {
            if ($userHost === null) {
                //nullのときはローカルなのでディレクトリの存在を確認できる。
                $file = new \SplFileInfo($file_name);
                if (!$file->isDir()) {
                    if (!mkdir($file_name)) {
                        throw new \RuntimeException("failed to mkdir.\n");
                    }
                }
                $this->path = $file->getRealPath();
                if ($this->path === false) {
                    throw new \RuntimeException("failed to get readPath.");
                }
            }

            $this->userHost = $userHost;
        }
    }