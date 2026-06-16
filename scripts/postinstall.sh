#!/bin/bash

if [ -d "$(pwd)/plugins/facebook-capi" ]; then
  ln -sfn "$(pwd)/plugins/facebook-capi" "$(pwd)/../../plugins/facebook-capi"
fi
