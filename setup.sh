#!/bin/bash
HOMEPATH=$HOME
CURRPATH=$(pwd)

backup() {
  mv $HOMEPATH/vim $HOMEPATH/.vim.backup
  mv $HOMEPATH/vimrc $HOMEPATH/.vimrc
}

setup() {
  cp -r $CURRPATH/vim $HOMEPATH/.vim
  cp -r $CURRPATH/vim/.vimrc $HOMEPATH/.vimrc
}

backup
setup
